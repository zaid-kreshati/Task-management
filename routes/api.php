<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController_api;
use App\Http\Controllers\api\UserController_api;
use App\Http\Controllers\api\TasksController_api;
use App\Http\Controllers\api\LogsController;
use App\Http\Controllers\api\CategoryController_api;
use App\Http\Controllers\api\TestEmailController_api;
use App\Http\Controllers\api\CommentController_api;
use App\Http\Controllers\api\SubTaskController_api;
use App\Http\Middleware\CheckLeader;
use App\Http\Middleware\CheckUserComment;
use App\Http\Middleware\ValidateToken;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth routes
Route::controller(AuthController_api::class)->group(function () {
    Route::post('/login', 'login')->name('api.login');
    Route::post('/register/user', 'register_user')->name('api.register.user');
    Route::post('/register/leader', 'register_leader')->middleware(ValidateToken::class,CheckLeader::class)->name('api.register.leader');

    Route::post('/logout', 'logout')->middleware(ValidateToken::class,'auth:api')->name('api.logout');
});

// Routes that require authentication
    Route::get('/logs', [LogsController::class, 'logs'])->middleware(ValidateToken::class,'auth:api',CheckLeader::class)->name('api.logs');

    // Task routes
    Route::controller(TasksController_api::class)->group(function () {
        Route::get('/tasks/list/{status}', 'tlist')->middleware(ValidateToken::class,'auth:api')->name('api.tasks.list');
        Route::get('/tasks/index', 'index')->middleware(ValidateToken::class,'auth:api')->name('api.tasks.index');
        Route::post('/tasks/search', 'search')->middleware(ValidateToken::class,'auth:api',)->name('api.tasks.search');

        Route::post('/tasks/store', 'store')->middleware( ValidateToken::class,'auth:api',CheckLeader::class )->name('api.tasks.store');


        Route::get('/tasks/details/{id}', 'task_details')->name('api.tasks.details');
        Route::post('/tasks/update/{id}', 'update')->middleware(ValidateToken::class,'auth:api',CheckLeader::class)->name('api.tasks.update');
        Route::delete('/tasks/destroy/{id}', 'destroy')->middleware(ValidateToken::class,'auth:api',CheckLeader::class)->name('api.tasks.destroy');
        Route::get('/tasks/restore/{id}', 'restore')->middleware(ValidateToken::class,'auth:api',CheckLeader::class)->name('api.tasks.restore');
    });

  // SubTask routes
Route::middleware([ValidateToken::class,'auth:api', CheckLeader::class])->group(function () {
    Route::controller(SubTaskController_api::class)->group(function () {
        Route::post('/subtasks/store', 'storeSubtask')->name('api.subtasks.store');
        Route::post('/subtasks/update/{id}', 'update_subtask')->name('api.subtasks.update');
        Route::delete('/subtasks/destroy/{id}', 'destroy_subtask')->name('api.subtasks.destroy');
        Route::get('/subtasks/restore/{id}', 'restore')->name('api.subtasks.restore');
    });
});


    // Category routes
    Route::controller(CategoryController_api::class)->group(function () {
        Route::post('/categories/store', 'store')->middleware(ValidateToken::class,'auth:api', CheckLeader::class)->name('api.categories.store');
        Route::get('/categories/index', 'index')->name('api.categories.index');
        Route::post('/categories/search', 'search')->name(ValidateToken::class,'api.categories.search');
        Route::post('/categories/update/{id}', 'update')->middleware(ValidateToken::class,'auth:api', CheckLeader::class)->name('api.categories.update');
        Route::delete('/categories/destroy/{id}', 'destroy')->middleware(ValidateToken::class,'auth:api', CheckLeader::class)->name('api.categories.destroy');
        Route::get('/categories/restore/{id}', 'restore')->middleware(ValidateToken::class,'auth:api', CheckLeader::class)->name('api.categories.restore');
    });

    // Comment routes
    Route::controller(CommentController_api::class)->group(function () {
        Route::get('/comments/index/{type}/{id}', 'index')->middleware(ValidateToken::class,'auth:api')->name('api.comments.index');
        Route::get('/comments/user', 'CommentByUser')->middleware(ValidateToken::class,'auth:api')->name('api.comments.user');
        Route::get('/comments/leader', 'CommentByLeader')->middleware(ValidateToken::class,'auth:api')->name('api.comments.leader');


        Route::post('/comments/store', 'store')->middleware(ValidateToken::class,'auth:api')->name('api.comments.store');
        Route::post('/comments/update/{id}', 'update')->middleware(ValidateToken::class,'auth:api', CheckUserComment::class)->name('api.comments.update');
        Route::delete('/comments/destroy/{id}', 'destroy')->middleware(ValidateToken::class,'auth:api', CheckUserComment::class)->name('api.comments.destroy');
        Route::get('/comments/restore/{id}', 'restore')->middleware(ValidateToken::class,'auth:api')->name('api.comments.restore');
    });
