<?php
use Acme\Shop\Models\Product;
use Acme\Shop\Models\Category;
use Acme\Shop\Models\Tag;
use Cms\Models\ThemeData;
use RainLab\Pages\Classes\Menu as PagesMenu;
use Cms\Classes\Theme;
use Acme\Settings\Models\Slider;
use Acme\Settings\Models\Testimonial;
use Acme\Settings\Models\Gallery;
use Acme\Settings\Models\Stock;
use Illuminate\Http\Request;

Route::prefix('/api')->group(function () {
  Route::get('/new-products', function () {
    $new_products = Product::select('id','title','image','price','sale_price','is_new','is_hit','code')->with(['categories'])->orderBy('sort_order', 'asc')->where('is_active', 1)->where('is_new', 1)->limit(8)->get();
    return $new_products;
  });
  Route::get('/popular-products', function () {
    $hit_products = Product::select('id','title','image','price','sale_price','is_new','is_hit','code')->with(['categories'])->orderBy('sort_order', 'asc')->where('is_active', 1)->where('is_hit', 1)->limit(8)->get();
    return $hit_products;
  });
  Route::get('/products/{ids}', function ($ids) {
    return Product::select('id','title','image','price','sale_price')->whereIn('id', explode(',', $ids))->get();
  });
  Route::post('/add-order', 'Acme\Shop\Classes\OrderController@add');
  Route::get('/categories', function () {
    return Category::orderBy('sort_order', 'asc')->where('is_show', 1)->withCount('products')->get();
  });
  Route::get('/settings/{name}', function ($name) {
    return ThemeData::select('data')->where('theme', $name)->first();
  });
  Route::get('/tags', function () {
    return Tag::select('title', 'slug')->orderBy('sort_order', 'asc')->limit(14)->get();
  });
  Route::get('/navbar', function () {
    $theme = Theme::getActiveTheme();
    $menu = PagesMenu::loadCached($theme, 'main');
    return $menu->items;
  });
  Route::get('/category/{slug}', function ($slug, Request $request) {
    $page = $request->get('page');
    $order = 'price';
    $dir = 'desc';

    if($request->get('order')) {
      $order = $request->get('order');
    }

    if($request->get('dir')) {
      $dir = $request->get('dir');
    }

    return Category::where('slug', $slug)->with(['products' => function($query) use ($page, $order, $dir) {
      $query->orderBy($order, $dir)->skip($page)->take(20)->get();
    }])->withCount('products')->first();
  });
  Route::get('/tag/{slug}', function ($slug, Request $request) {

    $page = $request->get('page');
    $order = 'price';
    $dir = 'desc';

    if($request->get('order')) {
      $order = $request->get('order');
    }

    if($request->get('dir')) {
      $dir = $request->get('dir');
    }

    return Tag::where('slug', $slug)->with(['products' => function($query) use ($page, $order, $dir) {
      $query->orderBy($order, $dir)->skip($page)->take(20)->get();
    }])->withCount('products')->first();
  });
  Route::get('/product/{id}', function ($id) {
    return Product::with(['categories', 'gallery', 'tags'])->with(['comments' => function($query) {
      $query->orderBy('sort_order', 'asc')->where('status', 1);
    }])->where('id', $id)->first();
  });
  Route::get('/slider', function () {
    return Slider::orderBy('sort_order', 'asc')->get();
  });
  Route::get('/testimonials', function () {
    return Testimonial::orderBy('sort_order', 'asc')->get();
  });
  Route::post('/send-message', 'Acme\Shop\Classes\OrderController@send');
  Route::get('/gallery/{page}', function ($page) {
    return Gallery::with(['images'])->where('page', $page)->first();
  });
  Route::get('/post/{name}', 'Acme\Shop\Classes\Posts@getPost');
  Route::get('/search/{query}', function ($query) {
    return Product::select('id','title','image','price','sale_price','is_new','is_hit','code')
    ->where('title','LIKE',"%{$query}%")
    ->orWhere('code','LIKE',"%{$query}%")
    ->orderBy('price', 'desc')->get();
  });
  Route::get('/stocks', function () {
    return Stock::orderBy('sort_order', 'asc')->where('is_active', 1)->get();
  });
  Route::post('/add-comment', 'Acme\Shop\Classes\CommentController@add');

  Route::post('/user/register', 'Acme\Shop\Classes\UserController@register')->middleware('web');
  Route::post('/user/auth', 'Acme\Shop\Classes\UserController@auth')->middleware('web');
  Route::get('/user/isUserLogin', 'Acme\Shop\Classes\UserController@isUserLogin');
  Route::post('/user/reset-password', 'Acme\Shop\Classes\UserController@resetPassword');
  Route::post('/user/restore-password', 'Acme\Shop\Classes\UserController@restorePassword');

  Route::group(['middleware' => 'RainLab\User\Classes\AuthMiddleware'], function () {
    Route::get('/user/userdata', 'Acme\Shop\Classes\UserController@userdata');
    Route::get('/user/logout', 'Acme\Shop\Classes\UserController@logout');
    Route::get('/user/orders', 'Acme\Shop\Classes\UserController@userOrders');
    Route::post('/user/edit', 'Acme\Shop\Classes\UserController@userEdit');
    Route::post('/user/change-password', 'Acme\Shop\Classes\UserController@userChangePassword');

    Route::post('/user/wish', 'Acme\Shop\Classes\WishController@wish');
    Route::post('/user/check-wish', 'Acme\Shop\Classes\WishController@checkwish');
    Route::get('/user/wish/{user}', 'Acme\Shop\Classes\WishController@wishlist');
    Route::get('/user/wish-count/{user}', 'Acme\Shop\Classes\WishController@wishcount');
  });

});
