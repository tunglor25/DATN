<?php

use App\Http\Controllers\Client\DetailController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\WishlistController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ReviewController;
use App\Http\Controllers\Client\VNPayController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\DiscountController;
use App\Http\Controllers\Client\AboutController;
use App\Http\Controllers\Client\AddressController;



Route::get('/', [HomeController::class, 'index'])->name('home');

// Trang giới thiệu
Route::get('/gioi-thieu', [AboutController::class, 'index'])->name('about');

//danh sach san phẩm
Route::get('/products', [ClientProductController::class, 'index'])->name('client.products.index');
Route::get('products/{product:slug}', [DetailController::class, 'detail'])->name('detail');

// danh giá

Route::get('/product/{productId}/order/{orderId}/review', [ReviewController::class, 'create'])
    ->middleware('auth')
    ->name('product.review');

Route::post('/reviews', [ReviewController::class, 'store'])
    ->middleware('auth')
    ->name('reviews.store');


// Route hiển thị trang tin tức
Route::get('/tin-tuc', [PostController::class, 'newsPage'])->name('posts.news');

// Route ajax lấy bài viết
Route::get('/tin-tuc/bai-viet/{id}', [PostController::class, 'getPost'])->name('posts.get');
Route::get('/tin-tuc/{slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/search-products', [HomeController::class, 'search']);

// hồ sơ người dùng
Route::prefix('ho-so')->middleware(['auth'])->group(function () {
    // Trang hồ sơ cá nhân
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/cap-nhat', [ProfileController::class, 'update'])->name('profile.update');

    // Wishlist (sản phẩm yêu thích)
    Route::get('/yeu-thich', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/yeu-thich', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/yeu-thich/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Đơn hàng
    Route::get('/don-hang', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/don-hang/ajax/{status}', [OrderController::class, 'getOrdersByStatus'])->name('orders.ajax');
    Route::post('/don-hang/{orderId}/huy', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/don-hang/{orderId}/mua-lai', [OrderController::class, 'buyAgain'])->name('orders.buy-again');
    Route::get('/don-hang/{orderId}', [OrderController::class, 'show'])->name('orders.show');

    // Địa chỉ
    Route::prefix('dia-chi')->name('addresses.')->group(function () {
        Route::get('/', [AddressController::class, 'index'])->name('index');
        Route::get('/them', [AddressController::class, 'create'])->name('create');
        Route::post('/', [AddressController::class, 'store'])->name('store');
        Route::get('/{address}/sua', [AddressController::class, 'edit'])->name('edit');
        Route::put('/{address}', [AddressController::class, 'update'])->name('update');
        Route::delete('/{address}', [AddressController::class, 'destroy'])->name('destroy');
        Route::post('/{address}/mac-dinh', [AddressController::class, 'setDefault'])->name('set-default');
        
        // API endpoints cho địa chỉ
        Route::get('/ajax/wards', [AddressController::class, 'getWards'])->name('get-wards');
        Route::get('/ajax/default', [AddressController::class, 'getDefaultAddress'])->name('get-default');
    });

    

});

// Giỏ hàng
Route::prefix('gio-hang')->middleware(['auth'])->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/them', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cap-nhat/{itemId}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/xoa/{itemId}', [CartController::class, 'removeItem'])->name('cart.remove');
    Route::delete('/xoa-tat-ca', [CartController::class, 'clear'])->name('cart.clear');
});

// Checkout
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/selected', [CheckoutController::class, 'checkoutSelected'])->name('checkout.selected');
    Route::post('/checkout/check-discount', [CheckoutController::class, 'checkDiscount'])->name('checkout.check-discount');
    Route::post('/checkout/clear-buy-again-session', [CheckoutController::class, 'clearBuyAgainSession'])->name('checkout.clear-buy-again-session');
    Route::get('/checkout/return-from-address', [CheckoutController::class, 'returnFromAddress'])->name('checkout.return-from-address');
    Route::post('/checkout/save-info', [CheckoutController::class, 'saveCheckoutInfo'])->name('checkout.save-info');
    Route::get('/checkout/issues', [CheckoutController::class, 'showIssues'])->name('checkout.issues');
    Route::get('/checkout/clear-session', [CheckoutController::class, 'clearSession'])->name('checkout.clear-session');
});

// VNPay payment routes
Route::middleware(['auth'])->group(function () {
    Route::post('/vnpay/create', [VNPayController::class, 'createPayment'])->name('vnpay.create');
    Route::post('/vnpay/continue', [VNPayController::class, 'continuePayment'])->name('vnpay.continue');
    Route::get('/vnpay/return', [VNPayController::class, 'return'])->name('vnpay.return');
    Route::post('/vnpay/ipn', [VNPayController::class, 'ipn'])->name('vnpay.ipn');
    Route::get('/vnpay/status', [VNPayController::class, 'checkStatus'])->name('vnpay.status');
    Route::get('/vnpay/check-connection', [VNPayController::class, 'checkConnection'])->name('vnpay.check-connection');
});

// Mã giảm giá routes
Route::prefix('ma-giam-gia')->group(function () {
    Route::get('/', [DiscountController::class, 'index'])->name('discounts.index');
    Route::post('/{discountId}/nhan', [DiscountController::class, 'claim'])->middleware(['auth'])->name('discounts.claim');
    Route::get('/cua-toi', [DiscountController::class, 'myDiscounts'])->middleware(['auth'])->name('discounts.my-discounts');
});

