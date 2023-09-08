<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedController extends Controller
{
    public function login(AuthRequest $request)
    {
        if(Auth::attempt($request->only('email', 'password'))){
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return response()
            ->json([
                'message' => 'Đăng nhập thành công',
                'access_token' => $token,
            ]);
        }
        else{
            return response([
                'message' => 'Email or Password incorrect'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Đăng xuất thành công',
        ]);
    }

    public function register(AuthRegisterRequest $request){
        $regUser = $request->validated();
        $regUser['password'] = bcrypt($request->password);
        $regUser['email'] = $request->email;
        $user = User::create($regUser);
        $token = $user->createToken('token')->plainTextToken;
        return response()
        ->json([
            'message' => 'Đăng ký thành công',
            'access_token' => $token,
        ]);
    }
}




