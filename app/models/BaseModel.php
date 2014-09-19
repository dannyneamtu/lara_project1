<?php

class BaseModel extends Eloquent {
    /**
     * clasa definita pentru a functiona form validation
     * Validarile se astfel la nivel de model si nu in controller
     */
    public $errors;

    public static function boot()
    {
        parent::boot();
        static::saving (function($post)
            {
                return $post->validate();
            }
        );
    }


public function validate()
{
    // function to validate the user input. Input rules are set in the model for the appropriete database table
    $validation = Validator::make($this->attributes, static::$rules);

    if($validation->passes()) return true;

    $this->errors = $validation->messages();

    return false;
}
}