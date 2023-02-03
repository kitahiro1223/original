<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\HomeController;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

use App\Models\User;

use App\Http\Controllers\PossessionsController;
use App\Http\Controllers\IncomesController;
use App\Http\Controllers\ExpendituresController;

// use App\Http\Controllers\LineLoginController;
// use App\Http\Controllers\LineMessengerController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ホーム、ログイン
Route::get("/", [App\Http\Controllers\HomeController::class, 'index']);

// ユーザー一覧
Route::get("user-list", [BooksController::class, 'user_list']);

// 所持金
Route::get("possession",    [PossessionsController::class, 'possession'   ]);

Route::get ("po-category",   [PossessionsController::class, 'po_category'  ]);
Route::post("po-category",   [PossessionsController::class, 'po_category'  ]);
// Route::get("po-detail",     [BooksController::class, 'po_detail'    ]);
Route::get ("po-add",           [PossessionsController::class, 'po_add']);
Route::post("po-add",           [PossessionsController::class, 'po_add']);
Route::get ("po-add-confirm",   [PossessionsController::class, 'po_add_confirm']);
Route::post("po-add-confirm",   [PossessionsController::class, 'po_add_confirm']);
Route::get ("po-add-comp",      [PossessionsController::class, 'po_add_comp']);
Route::post("po-add-comp",      [PossessionsController::class, 'po_add_comp']);

Route::post("po-delete",        [PossessionsController::class, 'po_delete']);

// 収入
Route::get ("income",           [IncomesController::class, 'income']);
Route::get ("in-category",      [IncomesController::class, 'in_category']);
Route::post("in-category",      [IncomesController::class, 'in_category']);
Route::get ("in-add",           [IncomesController::class, 'in_add']);
Route::post("in-add",           [IncomesController::class, 'in_add']);
Route::get ("in-add-confirm",   [IncomesController::class, 'in_add_confirm']);
Route::post("in-add-confirm",   [IncomesController::class, 'in_add_confirm']);
Route::get ("in-add-comp",      [IncomesController::class, 'in_add_comp']);
Route::post("in-add-comp",      [IncomesController::class, 'in_add_comp']);

Route::get ("in-detail",        [IncomesController::class, 'in_detail']);
Route::post("in-detail",        [IncomesController::class, 'in_detail']);

Route::get ("in-edit",          [IncomesController::class, 'in_edit']);
Route::post("in-edit",          [IncomesController::class, 'in_edit']);
Route::get ("in-edit-confirm",  [IncomesController::class, 'in_edit_confirm']);
Route::post("in-edit-confirm",  [IncomesController::class, 'in_edit_confirm']);
Route::get ("in-edit-comp",     [IncomesController::class, 'in_edit_comp']);
Route::post("in-edit-comp",     [IncomesController::class, 'in_edit_comp']);

Route::post("in-delete",        [IncomesController::class, 'in_delete']);

// 支出
Route::get("expenditure",       [ExpendituresController::class, 'expenditure']);
Route::get ("ex-sub-category",  [ExpendituresController::class, 'ex_sub_category']);
Route::post("ex-sub-category",  [ExpendituresController::class, 'ex_sub_category']);
Route::get ("ex-by-category",   [ExpendituresController::class, 'ex_by_category']);
Route::post("ex-by-category",   [ExpendituresController::class, 'ex_by_category']);
Route::get ("ex-add",           [ExpendituresController::class, 'ex_add']);
Route::post("ex-add",           [ExpendituresController::class, 'ex_add']);
Route::get ("ex-add-confirm",   [ExpendituresController::class, 'ex_add_confirm']);
Route::post("ex-add-confirm",   [ExpendituresController::class, 'ex_add_confirm']);
Route::get ("ex-add-comp",      [ExpendituresController::class, 'ex_add_comp']);
Route::post("ex-add-comp",      [ExpendituresController::class, 'ex_add_comp']);

Route::get ("ex-detail",        [ExpendituresController::class, 'ex_detail']);
Route::post("ex-detail",        [ExpendituresController::class, 'ex_detail']);

Route::get ("ex-edit",          [ExpendituresController::class, 'ex_edit']);
Route::post("ex-edit",          [ExpendituresController::class, 'ex_edit']);
Route::get ("ex-edit-confirm",  [ExpendituresController::class, 'ex_edit_confirm']);
Route::post("ex-edit-confirm",  [ExpendituresController::class, 'ex_edit_confirm']);
Route::get ("ex-edit-comp",     [ExpendituresController::class, 'ex_edit_comp']);
Route::post("ex-edit-comp",     [ExpendituresController::class, 'ex_edit_comp']);

Route::post("ex-delete",        [ExpendituresController::class, 'ex_delete']);

// 設定
Route::get("setting", [BooksController::class, 'setting']);
Route::get("account", [BooksController::class, 'account']);

Route::get("category-kinds",   [BooksController::class, 'category_kinds' ]);
Route::get ("categories",      [BooksController::class, 'categories' ]);
Route::post("categories",      [BooksController::class, 'categories' ]);
Route::get ("category-add",    [BooksController::class, 'category_add' ]);
Route::post("category-add",    [BooksController::class, 'category_add' ]);

Route::post("category-delete", [BooksController::class, 'category_delete']);

Route::get("logout_check", function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/home');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
