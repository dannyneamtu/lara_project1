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

// Check authentication prior to allowing the user access to the content
Route::group(array('before' => 'auth'), function()
    {
        Route::get('/', array('as' => 'home', 'uses' => 'HomeController@index'));
    }
);

Route::get('laravel-version', function()
{
$laravel = app();
return "Your Laravel version is ".$laravel::VERSION;
});

//Ruta pentru pagina de login
Route::get('login', function()
{
return View::make('login.loginform')
->with('title', 'Login');
// este numele viewului login.loginform; in folderul views trebuie sa avem folderul login si in el fisierul loginform.blade.php

});

//ruta pentru logarea din form
Route::post('login', 'UserController@login');

//ruta pentru logout
Route::get('logout', function()
    {
        Auth::logout();
        Session::flush();
        return Redirect::to('login')
            ->with('message', FlashMessage::DisplayAlert('Logout succesful', 'info'));
    }
);

//ruta pentru logarea din form
Route::get('signup', function()
    {
        return View::make('user.signup')
            ->with('title', 'Signup');
    }
);

//ruta pentru signup page
Route::post('signup', 'UserController@signup');

//ruta catre formularul de forgotten [password
Route::get('forgotpassword', function()
    {
        return View::make('user.forgotpassword')
            ->with('title', 'Password Reset');
    }
);

//Route sumit of forgotten password form to the UserController
Route::post('forgotpassword', 'UserController@forgotpassword');

//Route that uses the reset code to reset a users password
Route::get('resetpassword/{resetcode}', 'UserController@resetpassword');