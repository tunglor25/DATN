<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Services\VietnamAddressService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AddressController extends Controller
{
    use AuthorizesRequests;
    
    protected $addressService;

    public function __construct(VietnamAddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    // Hiển thị danh sách địa chỉ
    public function index()
    {
        $addresses = auth()->user()->addresses()->active()->orderBy('is_default', 'desc')->get();
        return view('client.addresses.index', compact('addresses'));
    }

    // Hiển thị form thêm địa chỉ
    public function create()
    {
        $provinces = $this->addressService->getProvinces();
        return view('client.addresses.create', compact('provinces'));
    }

    // Lưu địa chỉ mới
    public function store(Request $request)
    {
        // Set locale to Vietnamese for validation messages
        app()->setLocale('vi');
        
                $request->validate([
            'receiver_name' => 'required|string|max:100',
            'receiver_phone' => 'required|string|max:20',
            'province_code' => 'required|string|max:10',
            'province_name' => 'required|string|max:100',
            'ward_code' => 'required|string|max:10',
            'ward_name' => 'required|string|max:100',
            'street_address' => 'required|string',
            'is_default' => 'boolean'
        ]);

        $address = auth()->user()->addresses()->create($request->all());

        // Tự động đặt địa chỉ đầu tiên làm mặc định nếu user chưa có địa chỉ nào
        $totalAddresses = auth()->user()->addresses()->count();
        if ($totalAddresses === 1) {
            // Đây là địa chỉ đầu tiên, tự động đặt làm mặc định
            $address->setAsDefault();
        } elseif ($request->boolean('is_default')) {
            // Nếu không phải địa chỉ đầu tiên và user chọn làm mặc định
            $address->setAsDefault();
        }

        // Kiểm tra xem có cần redirect về checkout không
        $shouldRedirectToCheckout = false;
        $checkoutInfo = null;
        

        
        // Kiểm tra parameter from_checkout (ưu tiên cao nhất)
        if (request()->has('from_checkout')) {
            $shouldRedirectToCheckout = true;
            // Tạo checkout info từ các session hiện có
            $checkoutInfo = $this->createCheckoutInfoFromSessions();
            // Lưu flag from_checkout vào session
            session(['from_checkout' => true]);
            \Log::info('Address store - using from_checkout parameter', ['checkoutInfo' => $checkoutInfo]);
        }
        // Kiểm tra session pending_checkout_info
        elseif (session('pending_checkout_info')) {
            $checkoutInfo = session('pending_checkout_info');
            $shouldRedirectToCheckout = true;

        }
        // Kiểm tra session from_checkout
        elseif (session('from_checkout')) {
            $shouldRedirectToCheckout = true;
            // Tạo checkout info từ các session hiện có
            $checkoutInfo = $this->createCheckoutInfoFromSessions();

        }
        // Kiểm tra các session khác có thể cho biết user đang checkout
        elseif (session('buy_again_items') || session('selected_items')) {
            $shouldRedirectToCheckout = true;
            // Tạo checkout info từ các session hiện có
            $checkoutInfo = $this->createCheckoutInfoFromSessions();

        }
        
        if ($shouldRedirectToCheckout && $checkoutInfo) {
            // Kiểm tra timestamp nếu có
            if (isset($checkoutInfo['timestamp']) && (now()->timestamp - $checkoutInfo['timestamp'] > 1800)) {
                session()->forget(['pending_checkout_info', 'buy_again_items', 'buy_again_order_id', 'selected_items']);
                return redirect()->route('checkout.index')
                    ->with('warning', 'Phiên thanh toán đã hết hạn. Vui lòng chọn lại sản phẩm để thanh toán.');
            }
            
            // Kiểm tra tính khả dụng của sản phẩm nếu có thông tin chi tiết
            if (isset($checkoutInfo['product_id']) || isset($checkoutInfo['buy_again_items']) || isset($checkoutInfo['selected_items'])) {
                if (!$this->validateCheckoutSession($checkoutInfo)) {
                    $issues = $this->analyzeCheckoutSessionIssues($checkoutInfo);
                    session()->forget(['pending_checkout_info', 'buy_again_items', 'buy_again_order_id', 'selected_items']);
                    
                    // Lưu issues vào session để có thể hiển thị chi tiết
                    session(['checkout_issues' => $issues]);
                    
                    $message = 'Sản phẩm không còn khả dụng: ' . implode(', ', array_slice($issues, 0, 3));
                    if (count($issues) > 3) {
                        $message .= ' và ' . (count($issues) - 3) . ' vấn đề khác';
                    }
                    $message .= '. Vui lòng chọn sản phẩm khác.';
                    
                    return redirect()->route('checkout.index')
                        ->with('warning', $message);
                }
            }
            // Nếu có empty_checkout, không cần validate, chỉ redirect về checkout
            elseif (isset($checkoutInfo['empty_checkout'])) {
                // Skip validation for empty checkout
            }
            
            // Xóa session checkout info
            session()->forget(['pending_checkout_info', 'from_checkout']);
            
            // Tạo URL redirect về checkout
            $redirectUrl = $this->createCheckoutRedirectUrl($checkoutInfo);
            

            
            $successMessage = 'Địa chỉ đã được thêm thành công!';
            if ($totalAddresses === 1) {
                $successMessage .= ' Địa chỉ này đã được đặt làm địa chỉ mặc định.';
            }
            $successMessage .= ' Bạn có thể tiếp tục thanh toán.';
            
            return redirect($redirectUrl)
                ->with('success', $successMessage);
        }

        $successMessage = 'Địa chỉ đã được thêm thành công!';
        if ($totalAddresses === 1) {
            $successMessage .= ' Địa chỉ này đã được đặt làm địa chỉ mặc định.';
        }
        

        
        return redirect()->route('addresses.index')->with('success', $successMessage);
    }

    /**
     * Tạo URL redirect về checkout dựa trên thông tin session
     */
    private function createCheckoutRedirectUrl($checkoutInfo)
    {
        // Kiểm tra timestamp (session có hiệu lực trong 30 phút)
        if (isset($checkoutInfo['timestamp']) && (now()->timestamp - $checkoutInfo['timestamp'] > 1800)) {
            return route('checkout.index');
        }

        // Khôi phục thông tin checkout
        if (isset($checkoutInfo['buy_again_items'])) {
            session(['buy_again_items' => $checkoutInfo['buy_again_items']]);
            if (isset($checkoutInfo['buy_again_order_id'])) {
                session(['buy_again_order_id' => $checkoutInfo['buy_again_order_id']]);
            }
            return route('checkout.index');
        } elseif (isset($checkoutInfo['product_id'])) {
            // Tạo URL với query parameters
            $url = route('checkout.index') . '?product_id=' . $checkoutInfo['product_id'];
            if (isset($checkoutInfo['variant_id'])) {
                $url .= '&variant_id=' . $checkoutInfo['variant_id'];
            }
            if (isset($checkoutInfo['quantity'])) {
                $url .= '&quantity=' . $checkoutInfo['quantity'];
            }
            return $url;
        } elseif (isset($checkoutInfo['selected_items'])) {
            return route('checkout.index') . '?selected_items=' . $checkoutInfo['selected_items'];
        } elseif (isset($checkoutInfo['empty_checkout'])) {
            // Trường hợp không có thông tin checkout cụ thể, chỉ redirect về checkout
            return route('checkout.index');
        }

        return route('checkout.index');
    }

    /**
     * Kiểm tra tính khả dụng của thông tin checkout trong session
     */
    private function validateCheckoutSession($checkoutInfo)
    {
        // Kiểm tra buy_again_items
        if (isset($checkoutInfo['buy_again_items'])) {
            foreach ($checkoutInfo['buy_again_items'] as $item) {
                $product = \App\Models\Product::find($item['product_id']);
                if (!$product || !$product->is_active) {
                    return false;
                }
                
                // Kiểm tra variant nếu có
                if (isset($item['variant_id']) && $item['variant_id']) {
                    $variant = \App\Models\ProductVariant::find($item['variant_id']);
                    if (!$variant || $variant->product_id != $product->id) {
                        return false;
                    }
                }
                
                // Kiểm tra số lượng tồn kho
                if (isset($item['quantity']) && $item['quantity'] > 0) {
                    $availableStock = $variant ? $variant->stock : $product->stock;
                    if ($availableStock < $item['quantity']) {
                        return false;
                    }
                }
            }
        }
        
        // Kiểm tra product_id (mua ngay)
        if (isset($checkoutInfo['product_id'])) {
            $product = \App\Models\Product::find($checkoutInfo['product_id']);
            if (!$product || !$product->is_active) {
                return false;
            }
            
            // Kiểm tra variant nếu có
            if (isset($checkoutInfo['variant_id']) && $checkoutInfo['variant_id']) {
                $variant = \App\Models\ProductVariant::find($checkoutInfo['variant_id']);
                if (!$variant || $variant->product_id != $product->id) {
                    return false;
                }
            }
            
            // Kiểm tra số lượng
            $quantity = $checkoutInfo['quantity'] ?? 1;
            if ($quantity > 0) {
                $availableStock = $variant ? $variant->stock : $product->stock;
                if ($availableStock < $quantity) {
                    return false;
                }
            }
        }
        
        // Kiểm tra selected_items (từ giỏ hàng)
        if (isset($checkoutInfo['selected_items'])) {
            $selectedItemIds = explode(',', $checkoutInfo['selected_items']);
            $cart = \App\Models\Cart::where('user_id', auth()->id())->with(['items.product', 'items.variant'])->first();
            
            if (!$cart) {
                return false;
            }
            
            $selectedItems = $cart->items->whereIn('id', $selectedItemIds);
            if ($selectedItems->isEmpty()) {
                return false;
            }
            
            // Kiểm tra từng item
            foreach ($selectedItems as $item) {
                if (!$item->product || !$item->product->is_active) {
                    return false;
                }
                
                if ($item->variant && $item->variant->product_id != $item->product->id) {
                    return false;
                }
                
                $availableStock = $item->variant ? $item->variant->stock : $item->product->stock;
                if ($availableStock < $item->quantity) {
                    return false;
                }
            }
        }
        
        return true;
    }

    /**
     * Phân tích lý do sản phẩm không khả dụng
     */
    private function analyzeCheckoutSessionIssues($checkoutInfo)
    {
        $issues = [];
        
        // Kiểm tra buy_again_items
        if (isset($checkoutInfo['buy_again_items'])) {
            foreach ($checkoutInfo['buy_again_items'] as $item) {
                $product = \App\Models\Product::find($item['product_id']);
                if (!$product) {
                    $issues[] = "Sản phẩm không tồn tại";
                    continue;
                }
                
                if (!$product->is_active) {
                    $issues[] = "Sản phẩm '{$product->name}' đã bị vô hiệu hóa";
                    continue;
                }
                
                // Kiểm tra variant nếu có
                if (isset($item['variant_id']) && $item['variant_id']) {
                    $variant = \App\Models\ProductVariant::find($item['variant_id']);
                    if (!$variant) {
                        $issues[] = "Biến thể sản phẩm không tồn tại";
                        continue;
                    }
                    if ($variant->product_id != $product->id) {
                        $issues[] = "Biến thể sản phẩm không thuộc sản phẩm '{$product->name}'";
                        continue;
                    }
                }
                
                // Kiểm tra số lượng tồn kho
                if (isset($item['quantity']) && $item['quantity'] > 0) {
                    $availableStock = $variant ? $variant->stock : $product->stock;
                    if ($availableStock < $item['quantity']) {
                        $issues[] = "Sản phẩm '{$product->name}' chỉ còn {$availableStock} trong kho (yêu cầu: {$item['quantity']})";
                    }
                }
            }
        }
        
        // Kiểm tra product_id (mua ngay)
        if (isset($checkoutInfo['product_id'])) {
            $product = \App\Models\Product::find($checkoutInfo['product_id']);
            if (!$product) {
                $issues[] = "Sản phẩm không tồn tại";
            } elseif (!$product->is_active) {
                $issues[] = "Sản phẩm '{$product->name}' đã bị vô hiệu hóa";
            } else {
                // Kiểm tra variant nếu có
                if (isset($checkoutInfo['variant_id']) && $checkoutInfo['variant_id']) {
                    $variant = \App\Models\ProductVariant::find($checkoutInfo['variant_id']);
                    if (!$variant) {
                        $issues[] = "Biến thể sản phẩm không tồn tại";
                    } elseif ($variant->product_id != $product->id) {
                        $issues[] = "Biến thể sản phẩm không thuộc sản phẩm '{$product->name}'";
                    }
                }
                
                // Kiểm tra số lượng
                $quantity = $checkoutInfo['quantity'] ?? 1;
                if ($quantity > 0) {
                    $availableStock = $variant ? $variant->stock : $product->stock;
                    if ($availableStock < $quantity) {
                        $issues[] = "Sản phẩm '{$product->name}' chỉ còn {$availableStock} trong kho (yêu cầu: {$quantity})";
                    }
                }
            }
        }
        
        // Kiểm tra selected_items (từ giỏ hàng)
        if (isset($checkoutInfo['selected_items'])) {
            $selectedItemIds = explode(',', $checkoutInfo['selected_items']);
            $cart = \App\Models\Cart::where('user_id', auth()->id())->with(['items.product', 'items.variant'])->first();
            
            if (!$cart) {
                $issues[] = "Giỏ hàng không tồn tại";
            } else {
                $selectedItems = $cart->items->whereIn('id', $selectedItemIds);
                if ($selectedItems->isEmpty()) {
                    $issues[] = "Không tìm thấy sản phẩm đã chọn trong giỏ hàng";
                } else {
                    foreach ($selectedItems as $item) {
                        if (!$item->product) {
                            $issues[] = "Sản phẩm trong giỏ hàng không tồn tại";
                        } elseif (!$item->product->is_active) {
                            $issues[] = "Sản phẩm '{$item->product->name}' đã bị vô hiệu hóa";
                        } elseif ($item->variant && $item->variant->product_id != $item->product->id) {
                            $issues[] = "Biến thể sản phẩm không thuộc sản phẩm '{$item->product->name}'";
                        } else {
                            $availableStock = $item->variant ? $item->variant->stock : $item->product->stock;
                            if ($availableStock < $item->quantity) {
                                $issues[] = "Sản phẩm '{$item->product->name}' chỉ còn {$availableStock} trong kho (yêu cầu: {$item->quantity})";
                            }
                        }
                    }
                }
            }
        }
        
        return $issues;
    }

    /**
     * Tạo checkout info từ các session hiện có
     */
    private function createCheckoutInfoFromSessions()
    {
        $checkoutInfo = [
            'timestamp' => now()->timestamp,
            'return_to_checkout' => true
        ];

        // Nếu có buy_again_items từ session
        if (session('buy_again_items')) {
            $checkoutInfo['buy_again_items'] = session('buy_again_items');
            if (session('buy_again_order_id')) {
                $checkoutInfo['buy_again_order_id'] = session('buy_again_order_id');
            }
        }
        // Nếu có selected_items từ session
        elseif (session('selected_items')) {
            $checkoutInfo['selected_items'] = session('selected_items');
        }
        // Nếu không có session nào, tạo checkout info rỗng để redirect về checkout
        else {
            $checkoutInfo['empty_checkout'] = true;
        }

        return $checkoutInfo;
    }

    // Hiển thị form chỉnh sửa
    public function edit(Address $address)
    {
        $this->authorize('update', $address);
        
        $provinces = $this->addressService->getProvinces();
        $wards = $this->addressService->getWardsByCode($address->province_code);
        
        return view('client.addresses.edit', compact('address', 'provinces', 'wards'));
    }

    // Cập nhật địa chỉ
    public function update(Request $request, Address $address)
    {
        $this->authorize('update', $address);

        // Set locale to Vietnamese for validation messages
        app()->setLocale('vi');

        $request->validate([
            'receiver_name' => 'required|string|max:100',
            'receiver_phone' => 'required|string|max:20',
            'province_code' => 'required|string|max:10',
            'province_name' => 'required|string|max:100',
            'ward_code' => 'required|string|max:10',
            'ward_name' => 'required|string|max:100',
            'street_address' => 'required|string',
            'is_default' => 'boolean'
        ]);

        $address->update($request->all());

        if ($request->boolean('is_default')) {
            $address->setAsDefault();
        }

        return redirect()->route('addresses.index')->with('success', 'Địa chỉ đã được cập nhật!');
    }

    // Xóa địa chỉ
    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);

        if ($address->is_default) {
            return back()->with('error', 'Không thể xóa địa chỉ mặc định!');
        }

        if ($address->isUsedInOrders()) {
            return back()->with('error', 'Không thể xóa địa chỉ đã được sử dụng trong đơn hàng!');
        }

        $address->delete();
        return back()->with('success', 'Địa chỉ đã được xóa!');
    }

    // Đặt làm địa chỉ mặc định
    public function setDefault(Address $address)
    {
        $this->authorize('update', $address);
        
        $address->setAsDefault();
        return back()->with('success', 'Đã đặt làm địa chỉ mặc định!');
    }

    // API endpoints cho select options
    public function getWards(Request $request): JsonResponse
    {
        $provinceCode = $request->get('province_code');
        $provinceName = $request->get('province_name');
        

        
        if (!$provinceCode && !$provinceName) {

            return response()->json([]);
        }
        
        // Ưu tiên sử dụng tên tỉnh nếu có
        if ($provinceName) {
            $wards = $this->addressService->getWards($provinceName);
        } else {
            $wards = $this->addressService->getWardsByCode($provinceCode);
        }
        

        
        return response()->json($wards);
    }

    // Lấy địa chỉ mặc định cho checkout
    public function getDefaultAddress(): JsonResponse
    {
        $address = auth()->user()->defaultAddress;
        
        if (!$address) {
            return response()->json(['error' => 'Không có địa chỉ mặc định'], 404);
        }
        
        return response()->json([
            'id' => $address->id,
            'receiver_name' => $address->receiver_name,
            'receiver_phone' => $address->receiver_phone,
            'full_address' => $address->full_address,
            'short_address' => $address->short_address
        ]);
    }

}
