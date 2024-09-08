<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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

    public function changePassword(ChangePasswordRequest $request)
    {
        $validatedData = $request->validated();
        $user = auth()->user();
        if (!Hash::check($validatedData['old_password'], $user->password)) {
            return response()->json([
                'message' => 'Mật khẩu cũ không đúng',
            ], 400);
        }

        $user->update([
            'password' => bcrypt($validatedData['password'])
        ]);

        // Revoke the existing access token
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json([
            'message' => 'Đổi mật khẩu thành công. Vui lòng đăng nhập lại.',
        ]);
    }

    public function refreshToken(Request $request){
        $user = $request->user();
        $user->tokens()->delete();
        $token = $user->createToken('token')->plainTextToken;
        return response()->json(['access_token' => $token]);
    }

    public function user(Request $request){
        $user = $request->user();
        return response()->json($user);
    }

}




