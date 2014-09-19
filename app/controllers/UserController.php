<?php
class UserController extends BaseController {

    public $restful = true;
    // pentru a putea trimite requesturi REST

    /**
     * Display listing of the resource
     *
     * @return Response
     */
    public function login()
    {
    //set the user array to gather data from user form
        $userdata = array(
          'username' => Input::get('username'),
          'password' => Input::get('password')
        );

        /*
        $hashpassword = Hash::Make(Input::get('password'));
        var_dump($hashpassword);
        die();
        */

        if(Auth::check())
        {
            return Redirect::to('/');

        }

        if(Auth::attempt($userdata))
        {
                // cauta userul in baza de date
                $user = UserModel::find(Auth::user()->id);

                if($user->active == 0)
                {
                    //daca userul nu este activ
                    Auth::logout();
                    Session::flush();

                    return Redirect::to('login')
                        ->with('message', FlashMessage::DisplayAlert('Login successful', 'success'));
                }//if($user->active == '0')

                //daca userul este activ
                Session::put('current_user', Input::get('username'));
                Session::put('user_access', $user->access);
                Session::put('user_id', $user->id);

                return Redirect::to('/');

        }//if Auth
        else
        {
           return Redirect::to('login')
               ->with('message', FlashMessage::DisplayAlert('Incorrect username or password', 'danger'));
        }//else if Auth
    }//login


    /**
     * Display listing of the resource
     *
     * @return Response
     */
    public function signup()
    {
        // obtin data curenta
        $today = date("Y-m-d H:i:s");

        $userdata = array(
            'givenname' => Input::get('givenname'),
            'surname' => Input::get('surname'),
            'username' => Input::get('username'),
            'email' => Input::get('email'),
            'password' => Input::get('password'),
            'password_confirmation' => Input::get('password_confirmation')
        );

        //set validation rules
        $rules = array(
            'givenname' => 'alpha_num|max:50',
            'surname' => 'alpha_num|max:50',
            'username' => 'required|unique:users,username|alpha_dash|min:5',
            'email' => 'required|unique:users,email|email',
         /*   'password' => 'required|alpha_num|between:6,100|confirmed',
            'password_confirmation' => 'required|alpha_num|between:6,100', */
        );

        //run validation check
        $validator = Validator::make($userdata, $rules);


        if($validator->fails())
        {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }
        else
        {
            $user = new UserModel;
            $user->givenname = Input::get('givenname');
            $user->surname = Input::get('surname');
            $user->username = Input::get('username');
            $user->email = Input::get('email');
            $user->photo = 'No photo found';
            $user->password = Hash::make(Input::get('password'));
            $user->active = "1";
            $user->isdel = "0";
            $user->last_login = $today;
            $user->access = "User";

            $user->save();



        }//else if

        return Redirect::to('login')
            ->with('message', FlashMessage::DisplayAlert('User account created', 'success'));


    }//signup

    /**
     * Display listing of the resource
     *
     * @return Response
     */
    public function forgotpassword()
    {
        $userdata = array(
            'email' => Input::get('email')
        );
        //set the validation rules
        $rules = array(
            'email' => 'required|email',
        );

        $validator = Validator::make($userdata, $rules);

        if($validator->fails())
        {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }
        else
        {
            // if validation passes, process the form data
            // verific daca exista acel email in baza de date
            $user = UserModel::where('email', '=', Input::get('email'));

            if($user->count())
            {

                $xuser = $user->first();
                $id = $xuser->id;
                // generate a reset code and the temp password
                $resetcode = str_random(10);
                $passwd = str_random(15);

                //$user->password_temp = Hash::make($passwd);
                $xuser->surname = $resetcode;
                //salvez valorile in tabela
// NU AM REUSIT RUPTUL CAPULUI SA FAC UPDATA
                try
                {
                    $xuser->update();

                    $user = UserModel::where('id', '=', $id);
                    $user = $user->first();
                    var_dump($xuser->surname);
                    die();

                    // compun datele pe care le trimit spre formularul de email catre user
                    $data = array(
                        'email' => $xuser->email,
                        'firstname' => $xuser->givenname,
                        'lastname' => $xuser->surname,
                        'username' => $xuser->username,
                        'link' => URL::to('resetpassword', $resetcode),
                        'password' => $passwd,
                    );
/*
                    Mail::send('emails.auth.reminder', $data, function($message) use($user, $data)
                    {
                        $message->to($user->email, $user->givenname . ' ' . $user->lastname)
                        ->subject('Activeaza parola');
                    });
*/
                   return Redirect::to('login')
                       ->with('message', FlashMessage::DisplayAlert('User password reset link has been sent to your email address.'.$xuser->resetcode, 'info'));

                }//if user->save
                catch (Exception $e) {
                    var_dump($e->getMessage());
                }

            }// if($user->count())
/*
            //if the email address does not match an email address in the database the display feedback to the user
            return Redirect::to('forgotpassword')
                ->with('message', FlashMessage::DisplayAlert('Could not validate existing email address.', 'danger'));
        */
        }//else if($validator->fails())

    }//forgotpassword

    /**
     * @param $resetcode
     */
    public function resetpassword($resetcode)
    {
        $user = UserModel::where('resetcode', '=', $resetcode)
                    ->where('password_temp', '!=', '');

         if($user->count())
         {
             //set the user variable to the first user record
             $user = $user->first();

             $user->password = $user->password_temp;
             $user->password_temp = '';
             $user->resetcode = '';
             if($user->save())
             {
                 return Redirect::to('login')
                 ->with('message', FlashMessage::DisplayAlert('Your account has been reset. You can now log ', 'succes'));
             }
         }//ifuser->count
        else
        {
            return Redirect::to('login')
                ->with('message', FlashMessage::DisplayAlert('Could not revover account. Please contact the admin.', 'info'));
        }
    }//resetpassword
/*
 *http://culttt.com/2014/05/26/adding-social-authentication-laravel-4-application-part-1/
 * http://maxoffsky.com/code-blog/integrating-facebook-login-into-laravel-application/
 */

}//UserController Class