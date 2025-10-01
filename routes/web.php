<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\UserController;
use App\Http\Controllers\web\TasksController;
use App\Http\Controllers\web\HomeController;
use App\Http\Controllers\web\CategoryController;
use App\Http\Controllers\web\TestEmailController;
use App\Http\Controllers\web\CommentController;
use App\Http\Controllers\web\SubTaskController;
use App\Http\Controllers\web\StripeController;
use App\Http\Controllers\web\VoucherController;
use App\Http\Controllers\web\CouponController;
use App\Models\Task;
use App\Http\Controllers\web\LanguageController;



Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::post('/switch-language', [LanguageController::class, 'switchLanguage'])->name('language.switch');


Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login.form');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/register', 'showRegisterForm')->name('register.form');
    Route::post('/register/leader', 'register_leader')->middleware('auth:api')->name('register.leader');
    Route::post('/register/user', 'register_user')->name('register.user');
});


Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});
Route::get('/logs', [HomeController::class, 'logs'])->name('logs');



Route::controller(TasksController::class)->group(function () {
    Route::get('/tasks/list/{status}',  'tlist')->name('tasks.list');

    Route::get('/tasks', 'index')->name('tasks.index');

    Route::get('/tasks/search',  'search')->name('tasks.search');

    // Route::get('/task/create', 'createForm')->name('tasks.createForm');
    Route::post('/task/create', 'store')->name('tasks.store');

    Route::get(' /task/{task_id}/assign_users', 'assignUsersView')->name('tasks.assign');
    Route::post('/task/{task_id}/assign-users', 'assignUsers')->name('tasks.assign.users');

    Route::get('/task/{taskId}/assign', 'assignUsersView')->name('tasks.assign.form');

    Route::get('/task/{id}', 'task_details')->name('tasks.details');
    Route::delete('/task/{id}', 'destroy')->name('tasks.destroy');
    Route::get('/task/restore/{id}', 'restore')->name('tasks.restore');

    Route::get('/task/{id}/edit', 'edit')->name('tasks.edit');
    Route::put('/task/update/{id}', 'update')->name('tasks.update');
    Route::get('/task/search',  'search')->name('tasks.search');
});


Route::controller(SubTaskController::class)->group(function () {
    Route::delete('/subtask/{id}',  'destroy_subtask')->name('subtasks.destroy');
    Route::put('/subtasks/update/{id}',  'update_subtask')->name('subtasks.update');
    Route::post('/subtask', 'storeSubtask')->name('subtasks.store');
    Route::get('/subtask/restore/{id}', 'restore')->name('subtasks.restore');
});



Route::controller(CategoryController::class)->group(function () {
    Route::get('categories/index', 'index')->name('categories.index');
    Route::get('/categories', 'indexpagination')->name('categories.index.pagination');


    Route::post('categories', 'store')->name('categories.store');
    Route::get('/categories/search', 'search')->name('categories.search');


    Route::get('categories/check', 'check')->name('categories.check');
    Route::get('categories/{id}/check-update', 'checkUpdate')->name('categories.checkUpdate');
    Route::get('categories/{id}/check-delete', 'checkDelete')->name('categories.checkDelete');

    Route::get('categories/{id}/edit', 'edit')->name('categories.edit');
    Route::put('categories/update/{id}', 'update')->name('categories.update');

    Route::delete('categories/{id}', 'destroy')->name('categories.destroy');
    Route::get('category/restore/{id}', 'restore')->name('categories.restore');

    Route::post('tasks/{task_id}/assign-categories', 'assignCategories')->name('tasks.assignCategories');
    Route::get('categories/{id}/assign-categories', 'assigncategoryView')->name('categories.assignCategories');
});


Route::get('/send-test-email', [TestEmailController::class, 'sendEmail']);

Route::controller(CommentController::class)->group(function () {
    Route::get('comments/{type}/{id}', 'index')->name('comments.index');
    Route::post('comments', 'store')->name('comments.store');
    Route::put('comments/{id}', 'update')->name('comments.update');
    Route::delete('comments/{id}', 'destroy')->name('comments.destroy');
    Route::get('comment/restore/{id}', 'restore')->name('comments.restore');
});

Route::get('/create-checkout-session', [StripeController::class, 'pay'])->name('create-checkout-session');
Route::get('/discount', [StripeController::class, 'Discount'])->name('discount');
Route::post('/discount/create', [StripeController::class, 'updateDiscount'])->name('discount.create');
Route::post('/admin/discount-update', [StripeController::class, 'updateDiscountAjax'])->name('discount.update');
Route::post('/admin/discount-create', [StripeController::class, 'createDiscountAjax'])->name('discount.create');


Route::post('/voucher/discount', [VoucherController::class, 'discount'])->name('voucher.discount');
Route::get('/coupon', [CouponController::class, 'form'])->name('coupon');
Route::post('/coupons/update', [CouponController::class, 'update'])->name('coupons.update');   // Update a coupon
Route::post('/coupons/create', [CouponController::class, 'store'])->name('coupons.store');   // Create a new coupon
Route::post('/coupons/delete', [CouponController::class, 'destroy'])->name('coupons.delete');


Route::get('/payment/success', [TasksController::class, 'paymentSuccess'])->name('tasks.payment.success');
Route::get('/payment/cancel', [TasksController::class, 'paymentCancel'])->name('tasks.payment.cancel');
