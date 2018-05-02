<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class AuthController
 * @package App\Http\Controllers
 * @resource Authentication
 *
 * These are endpoints required for authentication
 */
class AuthController extends Controller
{
    use AuthenticatesUsers;

    public function username(){
        return 'staff_id';
    }

    public function login(LoginRequest $request){
        $data = $request->validated();

        $credentials = [
            'staff_id' => $data['username'],
            'password' => $data['password']
        ];

        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->failure('Invalid login details');
        }

        $payload = JWTAuth::getPayload($token);

        $user = UserResource::make(Auth::user());

        $data = [
            'access_token' => $token,
            'expires' => $payload['exp'],
            'user' => $user
        ];

        return $this->success('Your login was successful', $data);
    }
}
