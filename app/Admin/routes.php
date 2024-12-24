<?php

use Illuminate\Routing\Router;
use App\Admin\Controllers\LandingPageController;
use App\Admin\Controllers\MajorCategoryController;
use App\Admin\Controllers\CategoryController;
use App\Admin\Controllers\ColorController;
use App\Admin\Controllers\ConceptController;
use App\Admin\Controllers\SnsMenuController;
use App\Admin\Controllers\OtherDesignController;
use App\Admin\Controllers\OtherMajorCategoryController;
use App\Admin\Controllers\OtherCategoryController;


Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('landing_pages', LandingPageController::class);
    $router->resource('major_categories', MajorCategoryController::class);
    $router->resource('categories', CategoryController::class);
    $router->resource('colors', ColorController::class);
    $router->resource('concepts', ConceptController::class);
    $router->resource('sns_menus', SnsMenuController::class);
    $router->resource('other_design', OtherDesignController::class);
    $router->resource('other_major_categories', OtherMajorCategoryController::class);
    $router->resource('other_categories', OtherCategoryController::class);
});
