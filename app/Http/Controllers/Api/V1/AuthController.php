<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();

        $token = $user->createToken('Api Tutorial')->accessToken;

        return response()->json([
            'message' => 'success',
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $token = $user->createToken('Api Tutorial')->accessToken;

            return response()->json([
                'message' => 'success',
                'token' => $token
            ]);
        } else {
            return response()->json([
                'message' => 'fail'
            ]);
        }
    }

    public function profile()
    {
        $user = Auth::user();
        $data = new UserResource($user); //single

        //array
        //UserResource::collection($user);
        return response()->json([
            'message' => 'success',
            'data' => $data
        ]);
    }

    public function logout()
    {
        Auth::user()->token()->revoke();
        return response()->json([
            'message' => 'success'
        ]);
    }
}
