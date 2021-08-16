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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/product/{slug}', 'HomeController@single')->name('product.single');
route::get('/category/{slug}', 'CategoryController@index')->name('category.single');
route::get('/store/{slug}', 'StoreController@index')->name('store.single');

route::prefix('cart')->name('cart.')->group(function () {
    route::get('/','CartController@index')->name('index');
    route::post('add','CartController@add')->name('add');
    route::get('remove/{slug}','CartController@remove')->name('remove');
    route::get('cancel','CartController@cancel')->name('cancel');
});

route::prefix('checkout')->name('checkout.')->group(function() {
    route::get('/','CheckoutController@index')->name('index');
    route::post('/proccess','CheckoutController@process')->name('proccess');
    route::post('/thanks','CheckoutController@thanks')->name('thanks');
    route::post('/notification','CheckoutController@notification')->name('notification');
});


Route::get('my-orders','UserOrderController@index')->name('user.orders')->middleware('auth');

//Admin
Route::group(['middleware' => ['auth', 'access.control.store.admin']],function() {

    Route::prefix('admin')->name('admin.')->namespace('Admin')->group(function(){
        Route::resource('stores', 'StoreController');
        Route::resource('products', 'ProductController');
        Route::resource('categories', 'CategoryController');

        Route::post('photos/remove/','ProductPhotoController@removePhoto')->name('photo.remove');

        Route::get('orders/my/','OrdersController@index')->name('orders.my');

        Route::get('notifications', 'NotificationController@notifications')->name('notifications.index');
        Route::get('notifications/read-all', 'NotificationController@readAll')->name('notification.read.all');
        Route::get('notifications/read/{notification}', 'NotificationController@read')->name('notification.read');
    });
});

Auth::routes();












Route::get('/model', function () {
    // $user = \App\User::create(
    //     [
    //         'name' => 'Administrator',
    //         'email' => 'administrator@mail.com',
    //         'password' => bcrypt('123456')
    //     ]
    // );

    // $user = \App\User::find(1)->update(
    //     [
    //         'name' => 'Administrador',
    //     ]
    // );

    //criar uma loja para um usuário
    // $user = \App\User::find(10);
    // $store = $user->store()->create([
    //     'name'=>'Loja Teste',
    //     'description'=>'Loja teste de informática',
    //     'mobile_phone'=>'XX-XXXX-XXXX',
    //     'phone'=>'XX-XXXX-XXXX',
    //     'slug'=>'loja-teste'
    // ]);

    //Criar um produto para uma loja
    // $store = \App\Store::find(41);
    // $store->products()->create([
    //     'name' => 'NoteBook Dell G5',
    //     'description' => 'CORE I7 10a Geração',
    //     'body' => 'Qualquer coisa.',
    //     'price' => 9800.50,
    //     'slug' => 'notebook-dell-g5',
    // ]);

    //criar categoria
    // \App\Category::create([
    //     'name'=>'Games',
    //     'description'=>null,
    //     'slug'=>'games'
    // ]);

    // \App\Category::create([
    //     'name'=>'Notebooks',
    //     'description'=>null,
    //     'slug'=>'notebooks'
    // ]);

    // return \App\Category::all();

    //adicionar um produto para uma categoria
    // $product = \App\Product::find(49);
    // //--- adiciona referencia
    // $product->categories()->attach([2]);
    // //--- remove referencia
    // $product->categories()->detach([2]);
    // //--- metodo sync (identifica o que precisa fazer attach, detach ou update)
    // $product->categories()->sync([1,2]);
});
