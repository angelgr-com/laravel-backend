<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PassportAuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate request data
        $data = $this->validate($request, [
            'name' => 'required|min:2|max:64',
            'email' => 'required|unique:users|email|min:8|max:64',
            'password' => 'required|min:8|max:32|',
        ],
        [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'password.required' => 'Password is required.',
            'email.unique' => 'Email is already registered.',
            'name.min' => 'Name must have at least 2 characters',
            'name.max' => 'Name must have a maximum of 64 characters',
            'email.min' => 'Email must have at least 2 characters',
            'email.max' => 'Email must have a maximum of 64 characters',
            'password.min' => 'Password must have at least 2 characters',
            'password.max' => 'Password must have a maximum of 32 characters',
        ]
        );
        // Encrypt password before save it in database
        $data['password'] = bcrypt($request->password);
        // If data is validated, then save user in database
        $user = User::create($data);
        // Generate token
        $token = $user->createToken('PassportAuth')->accessToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
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
        $user = auth()->user();

        return response()->json(['user' => $user], 200);
    }
}
