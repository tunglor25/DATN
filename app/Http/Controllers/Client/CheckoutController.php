<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Discount;
use App\Models\OrderDiscount;
use App\Helpers\VatHelper;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\UserDiscount;
use App\Models\Address;
use App\Traits\StockManagement;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    use StockManagement;
    // Hiển thị trang checkout
    public function index(Request $request)
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login.form')
                ->with('warning', 'Vui lòng đăng nhập để tiếp tục thanh toán.');
        }

        // Tự động dọn dẹp session cũ nếu có
        $this->cleanupExpiredSessions();

        // Load user discounts cho select list
        $userDiscounts = collect();
        $defaultAddress = null;

        if (Auth::check()) {
            $userDiscounts = Auth::user()->userDiscounts()
                ->where('status', 'active')
                ->with('discount')
                ->get()
                ->filter(function ($userDiscount) {
                    $discount = $userDiscount->discount;

                    // Chỉ kiểm tra validation cơ bản khi load dropdown
                    // Không kiểm tra min_order_value vì chưa biết giá trị đơn hàng
                    if ($discount->is_active != 1) {
                        return false;
                    }

                    $now = \Carbon\Carbon::now();
                    if ($discount->starts_at && $now->lt($discount->starts_at)) {
                        return false;
                    }
                    if ($discount->expires_at && $now->gt($discount->expires_at)) {
                        return false;
                    }
                    if ($discount->usage_limit && $discount->used >= $discount->usage_limit) {
                        return false;
                    }

                    return true;
                });

            // Lấy địa chỉ mặc định của user
            $defaultAddress = Auth::user()->addresses()
                ->where('is_default', true)
                ->where('is_active', true)
                ->first();

            // Lưu thông tin checkout vào session để có thể quay lại sau khi thêm địa chỉ
            $this->saveCheckoutInfoToSession($request);

            // Kiểm tra nếu user chưa có địa chỉ nào
            if (Auth::user()->addresses()->count() == 0) {
                return redirect()->route('addresses.create', ['from_checkout' => 1])
                    ->with('warning', 'Bạn cần thêm địa chỉ giao hàng trước khi thanh toán. Sau khi thêm địa chỉ, bạn sẽ được chuyển về trang thanh toán.');
            }
        }

        // Nếu có buy_again_items từ session (mua lại đơn hàng)
        if (session('buy_again_items')) {
            $buyAgainItems = session('buy_again_items');
            $buyAgainOrderId = session('buy_again_order_id');

            // Tạo object giả lập cart cho view checkout
            $cart = (object) [
                'items' => collect($buyAgainItems)->map(function ($item) {
                    $product = Product::find($item['product_id']);
                    $variant = $item['variant_id'] ? ProductVariant::find($item['variant_id']) : null;

                    // Tạo OrderItem tạm thời để sử dụng method getProductImagePath
                    $tempOrderItem = new \App\Models\OrderItem();
                    $tempOrderItem->product = $product;
                    $tempOrderItem->variant = $variant;

                    return (object) [
                        'product_id' => $item['product_id'],
                        'product_variant_id' => $item['variant_id'],
                        'product_name' => $item['product_name'],
                        'product_image' => $item['product_image'] ?? '',
                        'variant_attributes' => $item['variant_attributes'] ?? collect(),
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ];
                }),
                'total_amount' => collect($buyAgainItems)->sum(function ($item) {
                    return $item['price'] * $item['quantity'];
                }),
                'total_items' => collect($buyAgainItems)->sum('quantity'),
                'is_buy_again' => true,
                'buy_again_order_id' => $buyAgainOrderId,
            ];

            return view('client.checkout.index', compact('cart', 'userDiscounts', 'defaultAddress'));
        }

        // Xóa session buy_again_items nếu không phải từ "Mua Lần Nữa"
        session()->forget(['buy_again_items', 'buy_again_order_id']);

        // Nếu có product_id (mua ngay)
        if ($request->filled('product_id')) {
            $product = Product::find($request->product_id);
            if (!$product) {
                return redirect()->route('client.products.index')->with('error', 'Sản phẩm không tồn tại!');
            }
            $variant = null;
            if ($request->filled('variant_id')) {
                $variant = ProductVariant::find($request->variant_id);
                if (!$variant) {
                    return redirect()->route('client.products.index')->with('error', 'Biến thể sản phẩm không tồn tại!');
                }
            }
            $quantity = max(1, (int)$request->input('quantity', 1));

            // Tạo OrderItem tạm thời để sử dụng method getProductImagePath
            $tempOrderItem = new \App\Models\OrderItem();
            $tempOrderItem->product = $product;
            $tempOrderItem->variant = $variant;

            // Tạo object giả lập cart cho view checkout
            $cart = (object) [
                'items' => [
                    (object) [
                        'product_id' => $product->id,
                        'product_variant_id' => $variant ? $variant->id : null,
                        'product_name' => $product->name,
                        'product_image' => $this->getProductImagePathForProduct($product, $variant),
                        'variant_attributes' => $variant ? $variant->attributeValues : collect(),
                        'quantity' => $quantity,
                        'price' => $variant ? $variant->price : $product->price,
                        'subtotal' => ($variant ? $variant->price : $product->price) * $quantity,
                    ]
                ],
                'total_amount' => ($variant ? $variant->price : $product->price) * $quantity,
                'total_items' => $quantity,
            ];
            return view('client.checkout.index', compact('cart', 'userDiscounts', 'defaultAddress'));
        }

        // Nếu có selected_items (từ giỏ hàng)
        if ($request->filled('selected_items')) {
            $selectedItemIds = explode(',', $request->selected_items);
            $cart = Cart::where('user_id', Auth::id())->with(['items.product', 'items.variant.attributeValues.attribute'])->first();
            if (!$cart) {
                return redirect()->route('cart.index')->with('error', 'Giỏ hàng không tồn tại!');
            }

            // Lọc chỉ những items được chọn
            $selectedItems = $cart->items->whereIn('id', $selectedItemIds);
            if ($selectedItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Không có sản phẩm nào được chọn!');
            }

            // Tạo cart object với chỉ selected items
            $cart->items = $selectedItems;
            $cart->total_amount = $selectedItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
            $cart->total_items = $selectedItems->sum('quantity');

            // Lưu selected items vào session để sử dụng khi process order
            session(['selected_items' => $request->selected_items]);

            return view('client.checkout.index', compact('cart', 'userDiscounts', 'defaultAddress'));
        }

        // Mặc định: lấy từ giỏ hàng
        $cart = Cart::where('user_id', Auth::id())->with(['items.product', 'items.variant.attributeValues.attribute'])->first();
        if (!$cart || $cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }
        return view('client.checkout.index', compact('cart', 'userDiscounts', 'defaultAddress'));
    }

    /**
     * Lưu thông tin checkout vào session để có thể quay lại sau
     */
    private function saveCheckoutInfoToSession(Request $request)
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
        // Nếu có product_id (mua ngay)
        elseif ($request->filled('product_id')) {
            $checkoutInfo['product_id'] = $request->product_id;
            if ($request->filled('variant_id')) {
                $checkoutInfo['variant_id'] = $request->variant_id;
            }
            $checkoutInfo['quantity'] = $request->input('quantity', 1);
        }
        // Nếu có selected_items (từ giỏ hàng)
        elseif ($request->filled('selected_items')) {
            $checkoutInfo['selected_items'] = $request->selected_items;
        }
        // Nếu không có thông tin nào
        else {
            $checkoutInfo['empty_checkout'] = true;
        }

        session(['pending_checkout_info' => $checkoutInfo]);
    }

    /**
     * Khôi phục thông tin checkout từ session
     */
    private function restoreCheckoutFromSession()
    {
        $checkoutInfo = session('pending_checkout_info');

        if (!$checkoutInfo || !isset($checkoutInfo['return_to_checkout'])) {
            return false;
        }

        // Kiểm tra timestamp (session có hiệu lực trong 30 phút)
        if (now()->timestamp - $checkoutInfo['timestamp'] > 1800) {
            session()->forget('pending_checkout_info');
            return false;
        }

        // Kiểm tra tính khả dụng của sản phẩm trước khi khôi phục
        if (!$this->validateCheckoutSession($checkoutInfo)) {
            session()->forget('pending_checkout_info');
            return false;
        }

        // Khôi phục thông tin checkout
        if (isset($checkoutInfo['buy_again_items'])) {
            session(['buy_again_items' => $checkoutInfo['buy_again_items']]);
            if (isset($checkoutInfo['buy_again_order_id'])) {
                session(['buy_again_order_id' => $checkoutInfo['buy_again_order_id']]);
            }
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
        }

        // Xóa session sau khi khôi phục
        session()->forget('pending_checkout_info');

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
                $product = Product::find($item['product_id']);
                if (!$product || !$product->is_active) {
                    return false;
                }

                // Kiểm tra variant nếu có
                if (isset($item['variant_id']) && $item['variant_id']) {
                    $variant = ProductVariant::find($item['variant_id']);
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
            $product = Product::find($checkoutInfo['product_id']);
            if (!$product || !$product->is_active) {
                return false;
            }

            // Kiểm tra variant nếu có
            if (isset($checkoutInfo['variant_id']) && $checkoutInfo['variant_id']) {
                $variant = ProductVariant::find($checkoutInfo['variant_id']);
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
            $cart = Cart::where('user_id', Auth::id())->with(['items.product', 'items.variant'])->first();

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
                $product = Product::find($item['product_id']);
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
                    $variant = ProductVariant::find($item['variant_id']);
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
            $product = Product::find($checkoutInfo['product_id']);
            if (!$product) {
                $issues[] = "Sản phẩm không tồn tại";
            } elseif (!$product->is_active) {
                $issues[] = "Sản phẩm '{$product->name}' đã bị vô hiệu hóa";
            } else {
                // Kiểm tra variant nếu có
                if (isset($checkoutInfo['variant_id']) && $checkoutInfo['variant_id']) {
                    $variant = ProductVariant::find($checkoutInfo['variant_id']);
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
            $cart = Cart::where('user_id', Auth::id())->with(['items.product', 'items.variant'])->first();

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
     * Quay lại checkout từ trang tạo địa chỉ
     */
    public function returnFromAddress()
    {
        $checkoutInfo = session('pending_checkout_info');

        if (!$checkoutInfo) {
            return redirect()->route('checkout.index')
                ->with('info', 'Không tìm thấy thông tin thanh toán. Vui lòng chọn sản phẩm để thanh toán.');
        }

        // Kiểm tra timestamp
        if (now()->timestamp - $checkoutInfo['timestamp'] > 1800) {
            session()->forget('pending_checkout_info');
            return redirect()->route('checkout.index')
                ->with('warning', 'Phiên thanh toán đã hết hạn. Vui lòng chọn lại sản phẩm để thanh toán.');
        }

        // Kiểm tra tính khả dụng
        if (!$this->validateCheckoutSession($checkoutInfo)) {
            $issues = $this->analyzeCheckoutSessionIssues($checkoutInfo);
            session()->forget('pending_checkout_info');

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

        $redirectUrl = $this->restoreCheckoutFromSession();

        if ($redirectUrl) {
            return redirect($redirectUrl)
                ->with('success', 'Đã khôi phục thông tin thanh toán. Bạn có thể tiếp tục thanh toán.');
        }

        return redirect()->route('checkout.index')
            ->with('info', 'Không tìm thấy thông tin thanh toán. Vui lòng chọn sản phẩm để thanh toán.');
    }



    /**
     * Hiển thị chi tiết vấn đề checkout
     */
    public function showIssues()
    {
        $issues = session('checkout_issues', []);

        if (empty($issues)) {
            return redirect()->route('checkout.index')
                ->with('info', 'Không có vấn đề nào để hiển thị.');
        }

        return view('client.checkout.issues', compact('issues'));
    }

    /**
     * Lưu thông tin checkout vào session (cho việc thêm địa chỉ mới)
     */
    public function saveCheckoutInfo(Request $request)
    {


        $checkoutInfo = $request->all();



        // Lưu vào session
        session(['pending_checkout_info' => $checkoutInfo]);



        return response()->json(['success' => true]);
    }

    /**
     * Xóa session checkout (cho user tự xóa khi cần)
     */
    public function clearSession()
    {
        session()->forget([
            'pending_checkout_info',
            'buy_again_items',
            'buy_again_order_id',
            'selected_items',
            'checkout_issues'
        ]);

        return redirect()->back()
            ->with('success', 'Đã xóa thông tin thanh toán tạm thời.');
    }

    /**
     * Tự động dọn dẹp session hết hạn
     */
    private function cleanupExpiredSessions()
    {
        // Kiểm tra và xóa pending_checkout_info hết hạn
        $checkoutInfo = session('pending_checkout_info');
        if ($checkoutInfo && isset($checkoutInfo['timestamp'])) {
            if (now()->timestamp - $checkoutInfo['timestamp'] > 1800) { // 30 phút
                session()->forget([
                    'pending_checkout_info',
                    'checkout_issues'
                ]);
            }
        }

        // Kiểm tra và xóa buy_again_items hết hạn (nếu không có order_id)
        $buyAgainItems = session('buy_again_items');
        $buyAgainOrderId = session('buy_again_order_id');
        if ($buyAgainItems && !$buyAgainOrderId) {
            // Nếu chỉ có buy_again_items mà không có order_id, có thể là session cũ
            // Xóa sau 1 giờ để tránh xóa nhầm
            $checkoutInfo = session('pending_checkout_info');
            if (!$checkoutInfo || (now()->timestamp - $checkoutInfo['timestamp'] > 3600)) {
                session()->forget([
                    'buy_again_items',
                    'buy_again_order_id'
                ]);
            }
        }

        // Xóa selected_items nếu không có pending_checkout_info
        if (session('selected_items') && !session('pending_checkout_info')) {
            session()->forget('selected_items');
        }
    }

    /**
     * Chuẩn bị danh sách items để kiểm tra tồn kho
     */
    private function prepareItemsForStockCheck($request)
    {
        $items = [];

        // Xử lý buy_again_items
        if (session('buy_again_items')) {
            foreach (session('buy_again_items') as $item) {
                $items[] = [
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity']
                ];
            }
        }

        // Xử lý mua ngay
        if ($request->filled('product_id')) {
            $items[] = [
                'product_id' => $request->product_id,
                'variant_id' => $request->filled('variant_id') ? $request->variant_id : null,
                'quantity' => $request->quantity ?? 1
            ];
        }

        // Xử lý giỏ hàng
        if (session('selected_items')) {
            $selectedItemIds = explode(',', session('selected_items'));
            $cart = Cart::where('user_id', Auth::id())->with('items')->first();
            $selectedItems = $cart->items->whereIn('id', $selectedItemIds);

            foreach ($selectedItems as $item) {
                $items[] = [
                    'product_id' => $item->product_id,
                    'variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity
                ];
            }
        }

        return $items;
    }

    // Xử lý đặt hàng với transaction
    public function process(Request $request)
    {
        $reservedItems = null;

        try {
            // Xử lý thông tin địa chỉ
            $shippingName = $request->name;
            $shippingPhone = $request->phone;
            $shippingAddress = $request->address;

            // Nếu có chọn địa chỉ từ sổ địa chỉ
            if ($request->filled('selected_address_id')) {
                $selectedAddress = Address::where('id', $request->selected_address_id)
                    ->where('user_id', Auth::id())
                    ->where('is_active', true)
                    ->first();

                if ($selectedAddress) {
                    $shippingName = $selectedAddress->receiver_name;
                    $shippingPhone = $selectedAddress->receiver_phone;
                    $shippingAddress = $selectedAddress->full_address;
                }
            }

            $request->validate([
                'payment_method' => 'required|in:cod,bank,vnpay',
                'user_discount_id' => 'nullable|exists:user_discounts,id',
                'selected_address_id' => 'required|exists:addresses,id',
            ]);

            // Chuẩn bị danh sách items cần kiểm tra
            $itemsToCheck = $this->prepareItemsForStockCheck($request);

            // Kiểm tra tồn kho trước khi reserve
            $stockCheck = $this->checkStockAvailability($itemsToCheck);
            $unavailableItems = array_filter($stockCheck, function ($item) {
                return !$item['available'];
            });

            if (!empty($unavailableItems)) {
                $messages = array_column($unavailableItems, 'message');
                $errorMessage = "Một số sản phẩm không đủ tồn kho: " . implode(', ', $messages);

                if ($request->payment_method === 'vnpay') {
                    return response()->json(
                        $errorMessage,
                        400,
                        [],
                        JSON_UNESCAPED_UNICODE
                    )->header('Content-Type', 'application/json');
                } else {
                    throw new \Exception($errorMessage);
                }
            }

            // Reserve stock với transaction
            $reservedItems = $this->reserveStock($itemsToCheck);

            // Nếu checkout từ 'MUA LẠI ĐƠN HÀNG'
            if (session('buy_again_items')) {
                $buyAgainItems = session('buy_again_items');
                $buyAgainOrderId = session('buy_again_order_id');

                $subtotal = collect($buyAgainItems)->sum(function ($item) {
                    return $item['price'] * $item['quantity'];
                });
                $shipping = 0;
                $discount = 0;

                // Xử lý mã giảm giá cho mua lại
                $userDiscount = null;
                $discountAmount = 0;
                if ($request->filled('user_discount_id')) {
                    $userDiscount = UserDiscount::where('id', $request->user_discount_id)
                        ->where('user_id', Auth::id())
                        ->where('status', 'active')
                        ->with('discount')
                        ->first();

                    if ($userDiscount && $userDiscount->discount->isValid($subtotal)) {
                        $discountAmount = $userDiscount->discount->calculateDiscount($subtotal);
                        $discount = $discountAmount;
                    }
                }

                // Tính VAT sau khi áp dụng giảm giá
                $afterDiscount = $subtotal - $discount;
                $tax = VatHelper::calculateVat($afterDiscount);
                $total = $afterDiscount + $tax + $shipping;

                // Tạo đơn hàng
                $order = Order::create([
                    'order_number'     => $this->generateOrderNumber(),
                    'user_id'          => Auth::id(),
                    'subtotal'         => $subtotal,
                    'tax_amount'       => $tax,
                    'shipping_fee'     => $shipping,
                    'discount_amount'  => $discount,
                    'total_amount'     => $total,
                    'payment_method'   => $request->payment_method,
                    'payment_status'   => $request->payment_method === 'vnpay' ? 'pending' : 'pending',
                    'shipping_address' => $shippingAddress,
                    'shipping_phone'   => $shippingPhone,
                    'shipping_name'    => $shippingName,
                    'notes'            => $request->note,
                ]);

                // Tạo order items từ buy_again_items
                foreach ($buyAgainItems as $item) {
                    OrderItem::create([
                        'order_id'           => $order->id,
                        'product_id'         => $item['product_id'],
                        'product_variant_id' => $item['variant_id'],
                        'product_name'       => $item['product_name'],
                        'product_image'      => $item['product_image'] ?? '',
                        'variant_sku'        => $item['variant_sku'],
                        'price'              => $item['price'],
                        'quantity'           => $item['quantity'],
                        'subtotal'           => $item['price'] * $item['quantity'],
                        'variant_attributes' => $item['variant_attributes'] ?? [],
                    ]);
                }

                // Xử lý mã giảm giá nếu có
                if ($userDiscount) {
                    OrderDiscount::create([
                        'order_id' => $order->id,
                        'discount_id' => $userDiscount->discount->id,
                        'discount_amount' => $discountAmount,
                    ]);

                    // Đánh dấu user discount đã sử dụng
                    $userDiscount->markAsUsed();
                }

                // Xóa session buy_again_items sau khi tạo đơn hàng thành công
                session()->forget(['buy_again_items', 'buy_again_order_id']);

                // Xử lý thanh toán
                if ($request->payment_method === 'vnpay') {
                    // Trả về JSON response để JavaScript xử lý
                    return response()->json([
                        'success' => true,
                        'order_id' => $order->id,
                        'message' => 'Đơn hàng đã được tạo, đang chuyển đến cổng thanh toán VNPay...'
                    ]);
                } else {
                    return redirect()->route('orders.index')->with('success', 'Đặt hàng thành công! Mã đơn hàng: ' . $order->order_number);
                }
            }

            // Nếu checkout từ 'MUA NGAY'
            if ($request->filled('product_id')) {
                try {
                    $product = Product::find($request->product_id);
                    if (!$product) {
                        return redirect()->route('client.products.index')->with('error', 'Sản phẩm không tồn tại!');
                    }
                    $variant = null;
                    if ($request->filled('variant_id')) {
                        $variant = ProductVariant::find($request->variant_id);
                        if (!$variant) {
                            return redirect()->route('client.products.index')->with('error', 'Biến thể sản phẩm không tồn tại!');
                        }
                    }
                    $quantity = max(1, (int)$request->input('quantity', 1));
                    $price = $variant ? $variant->price : $product->price;
                    // Tồn kho đã được kiểm tra và giảm trong reserveStock() method
                    $subtotal = $price * $quantity;
                    $shipping = 0;
                    $discount = 0;

                    // Xử lý mã giảm giá cho mua ngay
                    $userDiscount = null;
                    $discountAmount = 0;
                    if ($request->filled('user_discount_id')) {
                        $userDiscount = UserDiscount::where('id', $request->user_discount_id)
                            ->where('user_id', Auth::id())
                            ->where('status', 'active')
                            ->with('discount')
                            ->first();

                        if ($userDiscount && $userDiscount->discount->isValid($subtotal)) {
                            $discountAmount = $userDiscount->discount->calculateDiscount($subtotal);
                            $discount = $discountAmount;
                        }
                    }

                    // Tính VAT sau khi áp dụng giảm giá
                    $afterDiscount = $subtotal - $discount;
                    $tax = VatHelper::calculateVat($afterDiscount);
                    $total = $afterDiscount + $tax + $shipping;

                    // Tạo đơn hàng
                    $order = Order::create([
                        'order_number'     => $this->generateOrderNumber(),
                        'user_id'          => Auth::id(),
                        'subtotal'         => $subtotal,
                        'tax_amount'       => $tax,
                        'shipping_fee'     => $shipping,
                        'discount_amount'  => $discount,
                        'total_amount'     => $total,
                        'payment_method'   => $request->payment_method,
                        'payment_status'   => $request->payment_method === 'vnpay' ? 'pending' : 'pending',
                        'shipping_address' => $shippingAddress,
                        'shipping_phone'   => $shippingPhone,
                        'shipping_name'    => $shippingName,
                        'notes'            => $request->note,
                    ]);

                    OrderItem::create([
                        'order_id'           => $order->id,
                        'product_id'         => $product->id,
                        'product_variant_id' => $variant ? $variant->id : null,
                        'product_name'       => $product->name,
                        'product_image'      => $this->getProductImagePathForProduct($product, $variant),
                        'variant_sku'        => $variant ? $variant->sku : null,
                        'price'              => $price,
                        'quantity'           => $quantity,
                        'subtotal'           => $subtotal,
                        'variant_attributes' => $variant ? $variant->attributeValues->mapWithKeys(function ($attr) {
                            return [$attr->attribute->name => $attr->value];
                        })->toJson() : null,
                    ]);
                    // Tồn kho đã được giảm trong reserveStock() method

                    // Lưu thông tin mã giảm giá nếu có (cho mua ngay)
                    if ($userDiscount && $discountAmount > 0) {
                        OrderDiscount::create([
                            'order_id' => $order->id,
                            'discount_id' => $userDiscount->discount->id,
                            'discount_amount' => $discountAmount,
                        ]);

                        // Đánh dấu user discount đã sử dụng
                        $userDiscount->markAsUsed();

                        // Tăng số lần sử dụng của mã giảm giá
                        $userDiscount->discount->incrementUsage();
                    }
                } catch (\Exception $e) {
                    Log::error('Lỗi khi tạo đơn hàng mua ngay', [
                        'user_id' => Auth::id(),
                        'product_id' => $request->product_id,
                        'variant_id' => $request->variant_id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);

                    if ($request->payment_method === 'vnpay') {
                        return response()->json([
                            'success' => false,
                            'message' => 'Có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại.'
                        ], 500, [], JSON_UNESCAPED_UNICODE)->header('Content-Type', 'application/json');
                    } else {
                        return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại.');
                    }
                }

                // KHÔNG clear giỏ hàng, KHÔNG kiểm tra giỏ hàng trống

                // Xử lý thanh toán cho mua ngay
                if ($request->payment_method === 'vnpay') {
                    try {
                        // Xóa session cho mua ngay
                        session()->forget([
                            'pending_checkout_info',
                            'checkout_issues'
                        ]);

                        return response()->json([
                            'success' => true,
                            'order_id' => $order->id,
                            'message' => 'Đơn hàng đã được tạo, đang chuyển đến cổng thanh toán VNPay...'
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Lỗi khi tạo JSON response cho VNPay', [
                            'order_id' => $order->id,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);

                        return response()->json([
                            'success' => false,
                            'message' => 'Có lỗi xảy ra khi xử lý thanh toán. Vui lòng thử lại.'
                        ], 500, [], JSON_UNESCAPED_UNICODE)->header('Content-Type', 'application/json');
                    }
                } else {
                    // Xóa session cho mua ngay (COD/Bank)
                    session()->forget([
                        'pending_checkout_info',
                        'checkout_issues'
                    ]);

                    return redirect()->route('orders.index')->with('success', 'Đặt hàng thành công! Mã đơn hàng: ' . $order->order_number);
                }
            }

            // Mặc định: checkout toàn bộ giỏ hàng hoặc selected items
            $cart = Cart::where('user_id', Auth::id())->with('items.variant.attributeValues.attribute', 'items.product')->first();
            if (!$cart || $cart->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
            }

            // Nếu có selected_items trong session (từ checkout selected)
            $selectedItemIds = session('selected_items');
            if ($selectedItemIds) {
                $selectedItems = $cart->items->whereIn('id', explode(',', $selectedItemIds));
                if ($selectedItems->isEmpty()) {
                    return redirect()->route('cart.index')->with('error', 'Không có sản phẩm nào được chọn!');
                }
                $cart->items = $selectedItems;
            }

            // Nếu có selected_items trong request (từ form checkout)
            if ($request->filled('selected_items')) {
                $selectedItemIds = explode(',', $request->selected_items);
                $selectedItems = $cart->items->whereIn('id', $selectedItemIds);
                if ($selectedItems->isEmpty()) {
                    return redirect()->route('cart.index')->with('error', 'Không có sản phẩm nào được chọn!');
                }
                $cart->items = $selectedItems;
            }

            $subtotal = $cart->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });
            $shipping = 0;
            $discount = 0;

            // Xử lý mã giảm giá
            $userDiscount = null;
            $discountAmount = 0;
            if ($request->filled('user_discount_id')) {
                $userDiscount = UserDiscount::where('id', $request->user_discount_id)
                    ->where('user_id', Auth::id())
                    ->where('status', 'active')
                    ->with('discount')
                    ->first();

                if ($userDiscount && $userDiscount->discount->isValid($subtotal)) {
                    $discountAmount = $userDiscount->discount->calculateDiscount($subtotal);
                    $discount = $discountAmount;
                }
            }

            // Tính VAT sau khi áp dụng giảm giá
            $afterDiscount = $subtotal - $discount;
            $tax = VatHelper::calculateVat($afterDiscount);

            $total = $afterDiscount + $tax + $shipping;
            $order = Order::create([
                'order_number'     => $this->generateOrderNumber(),
                'user_id'          => Auth::id(),
                'subtotal'         => $subtotal,
                'tax_amount'       => $tax,
                'shipping_fee'     => $shipping,
                'discount_amount'  => $discount,
                'total_amount'     => $total,
                'payment_method'   => $request->payment_method,
                'payment_status'   => $request->payment_method === 'vnpay' ? 'pending' : 'pending',
                'shipping_address' => $shippingAddress,
                'shipping_phone'   => $shippingPhone,
                'shipping_name'    => $shippingName,
                'notes'            => $request->note,
            ]);
            foreach ($cart->items as $item) {
                $variant = $item->variant;
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name'       => $item->product_name,
                    'product_image'      => $this->getProductImagePath($item),
                    'variant_sku'        => $variant ? $variant->sku : null,
                    'price'              => $item->price,
                    'quantity'           => $item->quantity,
                    'subtotal'           => $item->price * $item->quantity,
                    'variant_attributes' => $variant ? $variant->attributeValues->mapWithKeys(function ($attr) {
                        return [$attr->attribute->name => $attr->value];
                    })->toJson() : null,
                ]);
                // Tồn kho đã được giảm trong reserveStock() method
            }

            // Lưu thông tin mã giảm giá nếu có
            if ($userDiscount && $discountAmount > 0) {
                OrderDiscount::create([
                    'order_id' => $order->id,
                    'discount_id' => $userDiscount->discount->id,
                    'discount_amount' => $discountAmount,
                ]);

                // Đánh dấu user discount đã sử dụng
                $userDiscount->markAsUsed();

                // Tăng số lần sử dụng của mã giảm giá
                $userDiscount->discount->incrementUsage();
            }

            // Nếu thanh toán VNPay, trả về JSON response và xóa giỏ hàng ngay lập tức
            if ($request->payment_method === 'vnpay') {
                // Xóa sản phẩm khỏi giỏ hàng ngay lập tức
                $this->removeItemsFromCart($order);

                return response()->json([
                    'success' => true,
                    'message' => 'Đơn hàng đã được tạo thành công!',
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ]);
            }

            // Nếu thanh toán COD hoặc bank, xóa sản phẩm khỏi giỏ hàng sau khi tạo đơn hàng thành công
            // Nếu checkout từ selected items, xóa những items đó khỏi giỏ hàng
            if (session('selected_items')) {
                $selectedItemIds = explode(',', session('selected_items'));
                CartItem::whereIn('id', $selectedItemIds)->delete();
                session()->forget('selected_items');
            }

            // Xóa tất cả session liên quan đến checkout sau khi đặt hàng thành công
            session()->forget([
                'pending_checkout_info',
                'buy_again_items',
                'buy_again_order_id',
                'checkout_issues'
            ]);

            return redirect()->route('orders.index')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            // Khôi phục tồn kho nếu có lỗi
            if ($reservedItems) {
                $this->restoreStock($reservedItems);
            }

            Log::error("Lỗi khi xử lý đặt hàng", [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Phân loại lỗi để hiển thị thông báo phù hợp
            $errorMessage = $e->getMessage();

            // Kiểm tra xem có phải là VNPay không
            $isVnpay = $request->payment_method === 'vnpay';

            // Nếu là lỗi tồn kho, hiển thị thông báo đặc biệt
            if (strpos($errorMessage, 'chỉ còn') !== false && strpos($errorMessage, 'trong kho') !== false) {
                if ($isVnpay) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ], 400, [], JSON_UNESCAPED_UNICODE)->header('Content-Type', 'application/json');
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', $errorMessage);
                }
            }

            // Nếu là lỗi database (deadlock, timeout), hiển thị thông báo khác
            if (strpos($errorMessage, 'deadlock') !== false || strpos($errorMessage, 'timeout') !== false) {
                if ($isVnpay) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Hệ thống đang bận, vui lòng thử lại sau vài giây.'
                    ], 500, [], JSON_UNESCAPED_UNICODE)->header('Content-Type', 'application/json');
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Hệ thống đang bận, vui lòng thử lại sau vài giây.');
                }
            }

            // Lỗi khác
            if ($isVnpay) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại hoặc liên hệ hỗ trợ.'
                ], 500, [], JSON_UNESCAPED_UNICODE)->header('Content-Type', 'application/json');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại hoặc liên hệ hỗ trợ.');
            }
        }
    }

    // Xử lý checkout với selected items từ giỏ hàng
    public function checkoutSelected(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|string',
        ]);

        $selectedItemIds = explode(',', $request->selected_items);
        if (empty($selectedItemIds)) {
            return redirect()->route('cart.index')->with('error', 'Không có sản phẩm nào được chọn!');
        }

        // Lưu selected items vào session
        session(['selected_items' => $request->selected_items]);

        // Chuyển hướng đến checkout với selected items
        return redirect()->route('checkout.index', ['selected_items' => $request->selected_items]);
    }

    // Kiểm tra mã giảm giá
    public function checkDiscount(Request $request)
    {
        $request->validate([
            'user_discount_id' => 'required|exists:user_discounts,id',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $userId = Auth::id();

        // Tìm user discount và kiểm tra quyền sở hữu
        $userDiscount = UserDiscount::where('id', $request->user_discount_id)
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->with('discount')
            ->first();

        if (!$userDiscount) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại hoặc không khả dụng!'
            ]);
        }

        $discount = $userDiscount->discount;

        if (!$discount->isValid($request->subtotal)) {
            $message = 'Mã giảm giá không hợp lệ!';

            // Kiểm tra lý do cụ thể
            $now = now();
            if ($discount->starts_at && $now->lt($discount->starts_at)) {
                $message = 'Mã giảm giá chưa có hiệu lực!';
            } elseif ($discount->expires_at && $now->gt($discount->expires_at)) {
                $message = 'Mã giảm giá đã hết hạn!';
            } elseif ($discount->usage_limit && $discount->used >= $discount->usage_limit) {
                $message = 'Mã giảm giá đã hết lượt sử dụng!';
            } elseif ($discount->min_order_value > 0 && $request->subtotal < $discount->min_order_value) {
                $message = 'Đơn hàng phải có giá trị tối thiểu ' . number_format($discount->min_order_value) . 'đ!';
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }

        $discountAmount = $discount->calculateDiscount($request->subtotal);
        $afterDiscount = $request->subtotal - $discountAmount;
        $taxAmount = VatHelper::calculateVat($afterDiscount);
        $finalTotal = VatHelper::calculateTotalWithVat($afterDiscount);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công!',
            'discount' => [
                'id' => $discount->id,
                'user_discount_id' => $userDiscount->id,
                'code' => $discount->code,
                'type' => $discount->type,
                'value' => $discount->value,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'final_total' => $finalTotal,
                'description' => $discount->type === 'fixed'
                    ? 'Giảm ' . number_format($discount->value) . 'đ'
                    : 'Giảm ' . $discount->value . '%'
            ]
        ]);
    }

    private function generateOrderNumber()
    {
        do {
            $year = now()->format('Y');
            $lastOrder = Order::whereYear('created_at', $year)->orderByDesc('id')->first();
            $nextId = $lastOrder ? ($lastOrder->id + 1) : 1;
            $orderNumber = 'ORD-' . $year . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            // Kiểm tra xem order number đã tồn tại chưa
            $exists = Order::where('order_number', $orderNumber)->exists();
        } while ($exists);

        return $orderNumber;
    }

    // Xóa session buy_again_items
    public function clearBuyAgainSession()
    {
        session()->forget(['buy_again_items', 'buy_again_order_id']);
        return response()->json(['success' => true]);
    }

    /**
     * Lấy đường dẫn ảnh sản phẩm từ CartItem
     */
    private function getProductImagePath($cartItem)
    {
        // Ưu tiên ảnh của variant
        if ($cartItem->product_variant_id && $cartItem->variant && !empty($cartItem->variant->image)) {
            return $cartItem->variant->image;
        }

        // Lấy ảnh gốc của sản phẩm
        if ($cartItem->product && !empty($cartItem->product->product_image)) {
            return $cartItem->product->product_image;
        }

        // Trả về chuỗi rỗng nếu không có ảnh
        return '';
    }

    /**
     * Lấy đường dẫn ảnh sản phẩm từ Product và Variant
     */
    private function getProductImagePathForProduct($product, $variant = null)
    {
        // Ưu tiên ảnh của variant
        if ($variant && !empty($variant->image)) {
            return $variant->image;
        }

        // Lấy ảnh gốc của sản phẩm
        if (!empty($product->product_image)) {
            return $product->product_image;
        }

        // Trả về chuỗi rỗng nếu không có ảnh
        return '';
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng ngay lập tức
     */
    private function removeItemsFromCart(Order $order)
    {
        if (!$order->user || !$order->user->cart) {
            return;
        }

        $cart = $order->user->cart;

        foreach ($order->items as $orderItem) {
            // Tìm cart item tương ứng
            $cartItem = $cart->items()
                ->where('product_id', $orderItem->product_id)
                ->where('product_variant_id', $orderItem->product_variant_id)
                ->first();

            if ($cartItem) {
                // Xóa cart item hoàn toàn
                $cartItem->delete();
            }
        }
    }
}
