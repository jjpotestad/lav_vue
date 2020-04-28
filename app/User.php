<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','surname', 'role', 'email', 'password','api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Validation messages
     *
     * @var array
     */
    public static $messages = [
        'name.required'=> 'El campo nombre es requerido',
        // 'name.alpha'=> 'El campo nombre solo debe contener letras',
        'surname.required'=> 'El campo apellido es requerido',
        // 'surname.alpha'=> 'El campo apellido solo debe contener letras',
        'email.required'=> 'El campo email es requerido',
        'email.email'=> 'El campo email no es valido',
        'email.unique'=> 'Ya existe el email en el sistema',
        'password.required'=> 'El campo password es requerido',
        'password.min'=> 'El password debe tener al menos 8 caracteres',
    ];

    /**
     * Create validation rules
     *
     * @var array
     */

    public static $create_rules = [
        'name' => 'required|alpha_dash|max:255',
        'surname' => 'required|alpha_dash|max:255',
        'email' => 'required|email|unique:users',
        'role' => 'required',
        'password' => 'required|min:8',
    ];
    
    /**
     * Update validation rules
     *
     * @var array
     */
    public static $update_rules = [
        'name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'role' => 'required',
    ];

            
}
