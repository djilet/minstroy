<?php

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


use App\Helpers\ModuleHelper;

Route::prefix('admin')->namespace("Admin")->name("admin.")->group(function() {

    Route::middleware('guest:admin')->group(function() {
        Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('login', 'Auth\LoginController@login');
    });

    Route::middleware('auth:admin')->group(function() {
        Route::get('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('/', 'AdminController@dashboard')->name('dashboard');
        Route::post('editor-lang', 'AdminController@editorLang')->name('editor.lang');
        Route::post('create-slug', 'AdminController@createSlug')->name('slug.create');

        Route::resource('profile', 'ProfileController', [ 'parameters' => [
            'profile' => 'id'
        ]]);
        Route::delete('profile/destroys', 'ProfileController@destroys')->name('profile.destroy.multi');

        Route::resource('menu', 'MenuController', [ 'parameters' => [
            'menu' => 'id'
        ]]);

        Route::resource('page', 'PageController', [ 'parameters' => [
            'page' => 'id'
        ]]);
        Route::post('page/{id}/active', 'PageController@setActive')->name('page.active');
        Route::put('page/sortable', 'PageController@sort')->name('page.sort');

        Route::resource('link', 'LinkController', [ 'parameters' => [
            'link' => 'id'
        ]]);
    });

});

//ModuleHelper::routes();

/*
 * Пока что такой роутер для статических страниц =(
 */

Route::prefix('{lang}')->middleware('locale')->group(function() {
    Route::redirect('/index.html', '/'.Request::segment(1));
    Route::get('/', 'PageController@indexPage')->name('index');
    Route::get('{slug}.html', 'PageController@rootPage')->name('page.static.root');
    Route::get('{path}/{slug}.html', 'PageController@innerPage')->where('path', '.*')->name('page.static');
});

Route::redirect('/index.html', '/');
Route::get('/', 'PageController@indexPage');
Route::get('{slug}.html', 'PageController@rootPage');
Route::get('{path}/{slug}.html', 'PageController@innerPage')->where('path', '.*');
