<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;


// router cho admin
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('attributes')->name('attributes.')->group(function () {
        Route::get('/', [AttributeController::class, 'index'])->name('index');
        Route::get('/{attribute}/edit', [AttributeController::class, 'edit'])->name('edit');
        Route::post('/', [AttributeController::class, 'store'])->name('store');
        Route::put('/{attribute}', [AttributeController::class, 'update'])->name('update');
        Route::delete('/{attribute}', [AttributeController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');

        Route::get('/trash', [ProductController::class, 'trash'])->name('trash');
        Route::post('/upload-image', [ProductController::class, 'uploadImage'])->name('upload_image');
        Route::post('{product}/restore', [ProductController::class, 'restore'])->name('restore');
        Route::delete('{product}/force-delete', [ProductController::class, 'forceDelete'])->name('force-delete');

        Route::get('{product}', [ProductController::class, 'show'])->name('show');
        Route::get('{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::put('{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('{product}', [ProductController::class, 'destroy'])->name('destroy');
    });


    Route::prefix('/categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::get('/trash', [CategoryController::class, 'trashed'])->name('trash');
        Route::post('/{id}/restore', [CategoryController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('force-delete');
    });

    //Post
    Route::prefix('post')->name('post.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::get('/{id}/show', [PostController::class, 'show'])->name('show');
        Route::post('/store', [PostController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PostController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [PostController::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [PostController::class, 'destroy'])->name('destroy');
        Route::get('/trash', [PostController::class, 'trash'])->name('trash');
        Route::patch('/{id}/restore', [PostController::class, 'restore'])->name('restore');
        Route::delete('/{id}/forceDelete', [PostController::class, 'forceDelete'])->name('forceDelete');
    });


    // Brand
    Route::prefix('brands')->name('brands.')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('index');
        Route::post('/store', [BrandController::class, 'store'])->name('store');
        Route::put('/{id}/update', [BrandController::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [BrandController::class, 'destroy'])->name('destroy');
        Route::get('/trash', [BrandController::class, 'trash'])->name('trash');
        Route::post('/{id}/restore', [BrandController::class, 'restore'])->name('restore');
        Route::delete('/{id}/forcedelete', [BrandController::class, 'forceDelete'])->name('forceDelete');
    });

    // User
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/{id}/status', [UserController::class, 'status'])->name('status');
        Route::get('/{id}/show', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [UserController::class, 'destroy'])->name('destroy');
        Route::post('{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggleStatus');
        Route::get('/trash', [UserController::class, 'trash'])->name('trash');
        Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [UserController::class, 'forceDelete'])->name('forceDelete');
    });
    //SlideSlide
    Route::prefix('slide')->name('slide.')->group(function () {
        Route::get('/', [SlideController::class, 'index'])->name('index');
        Route::post('/store', [SlideController::class, 'store'])->name('store');
        Route::put('/{slide}/update', [SlideController::class, 'update'])->name('update');
        Route::delete('/{slide}/destroy', [SlideController::class, 'destroy'])->name('destroy');
        Route::get('/trash', [SlideController::class, 'trash'])->name('trash');
        Route::post('/{id}/restore', [SlideController::class, 'restore'])->name('restore');
        Route::delete('/{id}/forceDelete', [SlideController::class, 'forceDelete'])->name('forceDelete');
    });

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/export', [OrderController::class, 'export'])->name('export');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::put('/{order}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::put('/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('updatePaymentStatus');
        Route::post('/{order}/refund', [OrderController::class, 'refund'])->name('refund');
        Route::post('/{order}/approve-return', [OrderController::class, 'approveReturn'])->name('approveReturn');
        Route::post('/{order}/reject-return', [OrderController::class, 'rejectReturn'])->name('rejectReturn');
    });

    //Discount
    Route::prefix('discount')->name('discount.')->group( function () {
        Route::get('/', [DiscountController::class, 'index'])->name('index');
        Route::get('/create', [DiscountController::class, 'create'])->name('create');
        Route::get('/{id}/show', [DiscountController::class, 'show'])->name('show');
        Route::post('/store', [DiscountController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [DiscountController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [DiscountController::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [DiscountController::class, 'destroy'])->name('destroy');
    });
    
    // Reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('/{user}', [ReviewController::class, 'show'])->name('show');
        Route::post('/{review}/hide-comment', [ReviewController::class, 'hideComment'])->name('hide-comment');
        Route::post('/{review}/toggle-hidden', [ReviewController::class, 'toggleHidden'])->name('toggle-hidden');
        Route::post('/{review}/toggle-verified', [ReviewController::class, 'toggleVerified'])->name('toggle-verified');
        Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');

    });
});