

<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::any('/', array('as'=>'home','uses'=>'HomeController@index'));

Route::any('login',  array('as'=>'login','before'=>'signed_in','uses'=>'UserController@login') );

Route::any('/shop/{username}', array('as'=>'shop','uses'=>'UserController@shop'));
Route::any('user/addfav', array('as'=>'addfav','uses'=>'UserController@addfav') );
Route::any('user/removefav', array('as'=>'removefav','uses'=>'UserController@removefav') );
Route::any('user/addcart', array('as'=>'addcart','uses'=>'ItemController@addcart') );

Route::any('cart', array('as'=>'cart','uses'=>'UserController@cart') );
Route::any('order', array('as'=>'order','uses'=>'UserController@order') );
Route::any('order/invoice/{buyerid}' ,array('as'=>'preinvoice','uses'=>'ItemController@preinvoice') );
Route::any('order/saveinvoice' ,array('as'=>'saveinvoice','uses'=>'ItemController@saveinvoice') );
Route::any('order/payment/{invoiceid}', array('as'=>'payment','uses'=>'ItemController@payment') );
Route::any('orderstatus/{orderid}', array('as'=>'orderstatus','uses'=>'ItemController@orderstatus') );

Route::any('item/image/{size}/{itemid}', array('as'=>'itemimage','uses'=>'ItemController@image') );
Route::any('item/view/{itemid}', array('as'=>'item','uses'=>'ItemController@item') );

Route::any('payment/{invoiceid}' ,array('as'=>'getpayment','uses'=>'ItemController@getpayment') );
Route::any('savepayment' ,array('as'=>'savepayment','uses'=>'ItemController@savepayment') );

Route::any('category/{catname?}', array('as'=>'category','uses'=>'ItemController@viewcat') );

Route::any('profile/{username?}', array('as'=>'profile','uses'=> 'UserController@profile'));
Route::any('user/add', array('before' => 'csrf', 'uses' => 'UserController@add') );
Route::any('user/signup', array('before'=>'signed_in','uses'=>'UserController@signup') );
Route::any('user/signin', 'UserController@signin');
Route::any('user/signinfb',  array('as'=>'fbsignin','uses'=>'UserController@signinfb'));
Route::any('user/connectfb',  array('as'=>'fbconnect','uses'=>'UserController@connectfb'));
Route::any('user/edit/{id}', array('as'=>'useredit','uses'=>'UserController@edit') );
Route::any('user/update/{id}', array('as'=>'userupdate','uses'=>'UserController@update') );
Route::any('user/activate/{token}', array('as'=>'activate','uses'=>'UserController@activate') );

Route::any('shop/update/{id}', array('as'=>'shopupdate','uses'=>'UserController@shopupdate') );


Route::any('user/autocomplete/{kw}', array('as'=>'userautocomplete','uses'=>'UserController@typeahead') );

Route::any('gym/autocomplete/{kw}', array('as'=>'gymautocomplete','uses'=>'GymController@autocomplete') );

Route::any('user/message',  array('as'=>'usermsg','uses'=>'UserController@message'));

Route::get('where',  array('as'=>'where','uses'=>'HomeController@where'));

Route::get('profile',  array('as'=>'profile','uses'=>'HomeController@profile'));
Route::get('about',  array('as'=>'about','uses'=>'HomeController@about'));

Route::get('webboard',  array('as'=>'forums','uses'=>'ForumsController@home'));
Route::get('category/{id}/{slug?}',  array('as'=>'category','uses'=>'ForumsController@category'));
Route::get('topic/{id}/{slug?}',  array('as'=>'topic','uses'=>'ForumsController@topic'));
Route::get('newtopic/{cat}',  array('as'=>'newtopic','uses'=>'ForumsController@newtopic'));



Route::any('create',  array('as'=>'create','uses'=>'HomeController@create'));

Route::any('jsconnect',  array('as'=>'jsconnect','uses'=>'VanillaController@index'));

Route::any('getlocation',  array('as'=>'getlocation','uses'=>'LocationController@index'));
Route::any('getlocationbyday',  array('as'=>'getlocationbyday','uses'=>'LocationController@getlocationbyday'));
Route::any('getgym',  array('as'=>'getgym','uses'=>'LocationController@getgym'));


Route::any('admin',array('before' => 'isadmin','as'=>'admin','uses'=>'ItemController@index'));
Route::any('newitem',array('as'=>'newitem','uses'=>'ItemController@newitem'));
Route::any('item/add',array('as'=>'additem','uses'=>'ItemController@add'));
Route::any('edit_content/{id}',array('as'=>'edit_content','uses'=>'ItemController@edit'));
Route::any('remove_content/{id}',array('as'=>'remove_content','uses'=>'ItemController@remove'));
Route::any('add_content',array('as'=>'add_content','uses'=>'ItemController@add'));
Route::any('uploaditem',array('as'=>'uploaditem','uses'=>'ItemController@upload'));



/* api route*/
Route::any('api/auth/login/', array('as'=>'loginapi','uses'=>'UserController@api_signin'));
Route::any('api/auth/user/', array('as'=>'getuserapi','uses'=>'UserController@api_getuser'));
Route::get('api/auth/logout',array('as'=>'logoutapi',
function() {
    Auth::logout();
    return json_encode(null);
} ) );

Route::filter('isadmin', function()
{
	
	
    if (!Auth::user()||Auth::user()->username!='admin')
    {
        return Redirect::route('home');
    }
});


Route::filter('authened', function()
{

    if (!Auth::user())
    {
        return Redirect::to('login');
    }
});

Route::filter('signed_in', function()
{
    if (Auth::user())
    {
        return Redirect::to('/');
    }
});

Route::get('logout',array('as'=>'logout',
function() {
    Auth::logout();
    return Redirect::to('/');
} ) );

