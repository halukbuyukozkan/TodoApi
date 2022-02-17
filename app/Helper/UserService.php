<?php


namespace App\Helper;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class UserService
{
    public $email, $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;

    }
    public function validateInput()
    {
        $validator = Validator::make(['email' => $this->email, 'password'=>$this->password],
        [
            'email' => ['required','email:rfc,dns','unique:users'],
            'password' => ['required','string', Password::min(8)]
        ]
        );

        if($validator->fails())
        {
            return ['status' => false, 'messages'=>$validator->messages()];
        }
        else
        {
            return ['status' => true];   
        }
    }


    public function register($deviceName)
    {
        $validate = $this->validateInput();
        if($validate['status'] == false)
        {
            return $validate;
        }
        else
        {
            $user = User::create(['email'=>$this->email, 'password'=>Hash::make($this->password)]);
            //laravel mobile api tokens
            $token = $user->createToken($deviceName)->plainText;
        }
    }
}





