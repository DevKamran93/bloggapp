<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Models\Admin\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});
// dd(Hash::make('admin123'));
Route::get('/', function () {
    return view('welcome');
});
// dd(Hash::make('12345'));
Route::get('/login', function () {
    return view('auth.login');
});
Auth::routes();
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/getAllCategories', [CategoryController::class, 'getAllCategoryData'])->name('category.getAllCategoryData');
    Route::patch('/categories/update', [CategoryController::class, 'update'])->name('category.update');
    Route::post('/categories/destroyOrRestore', [CategoryController::class, 'destroyOrRestore'])->name('category.destroyOrRestore');

    // Blog Routes
    Route::get('/blogs', [BlogController::class, 'index'])->name('blogs');
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog/store', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blogs/getAllBlogs', [BlogController::class, 'getAllBlogsData'])->name('blogs.getAllBlogsData');
    Route::get('/blog/edit/{slug}', [BlogController::class, 'edit'])->name('blog.edit');
    Route::patch('/blog/update', [BlogController::class, 'update'])->name('blog.update');
    Route::post('/blog/destroyOrRestore', [BlogController::class, 'destroyOrRestore'])->name('blog.destroyOrRestore');

    // Roles Routes
    Route::get('/roles', [RoleController::class, 'index'])->name('roles');
    Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
    Route::post('/role/store', [RoleController::class, 'store'])->name('role.store');
    Route::get('/role/getAllRoles', [RoleController::class, 'getAllRolesData'])->name('role.getAllRolesData');
    // Route::get('/blog/edit/{slug}', [RoleController::class, 'edit'])->name('blog.edit');
    Route::patch('/role/update', [RoleController::class, 'update'])->name('role.update');
    Route::post('/role/destroyOrRestore', [RoleController::class, 'destroyOrRestore'])->name('role.destroyOrRestore');

    // Route::post('/categories/restore', [CategoryController::class, 'restore'])->name('category.restore');
});
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
