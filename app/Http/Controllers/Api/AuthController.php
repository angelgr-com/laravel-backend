<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate request data
        $data = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:64',
            'email' => 'required|string|unique:users|email|min:8|max:64',
            'password' => 'required|string|min:8|max:32|',
            'username' => 'required|string|unique:users|min:2|max:32|',
            'steamUsername' => 'string|min:2|max:32|',
            'role' => 'string|min:4|max:5|',
        ]);

        if ($data->fails()){
            return response()->json(['message' => $data->errors()->first(), 'status' => false], 400);
        }

        // If data is validated, encrypt password and store user data
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->password),
            'username' => $request->get('username'),
            'steamUsername' => $request->get('steamUsername'),
            'role' => 'user',
        ]);

        return response()->json(['message' => 'User registered successfully'], 200);
    }

    public function login(Request $request)
    {
        // Validate request data
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        // Attempt to login user with provided data
        if (auth()->attempt($data)) {
            $user = auth()->user();
            $token = $user->createToken('PassportAuth')->accessToken;

            return response()->json(['user' => $user, 'token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function userInfo()
    {
        return response()->json(['user' => auth()->user()], 200);
    }
}
