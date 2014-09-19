<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class UserModel extends BaseModel implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

    //ii spun lui Laravel ce campuri se pot salva in tabela user
    protected $filable = array(
        'username',
        'email',
        'access',
        'isdel',
        'active',
        'givenname',
        'surname',
        'photo',
        'password_temp',
        'resetcode',
        'last_login' );

    /**
     * Get the unique identifier for the user
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }//getAuthIdentifier

    /**
     * Get the password for the user
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }//getAuthPassword

    /**
     * Set the token value for the "remember me" session
     * @return string
     *
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }//getRememberToken

    /**
     * Set the token value from the "remember me" session.
     * @param string $value
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }//setRememberToken

    /**
     * Get the column name for the "remember me" token
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }//getRememberTokenName

    public static $rules = array(
        'givenname' => 'alpha_num|max:50',
        'surname' => 'alpha_num|max:50',
        'username' => 'required|unique:users,username|alpha_dash|min:5',
        'email' => 'required|unique:users,email|email',
     /*   'password' => 'required|alpha_num|between:6,100|confirmed',
        'password_confirmation' => 'required|alpha_num|between:6,100',*/
    );
    /* NU merge regula de validare pentru confirmarea de parola */
}
