<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Mail\ForgetMail;
use App\Http\Requests\ForgetRequest;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ResetRequest;
use Illuminate\Support\Facades\Hash;

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

    public function forget(ForgetRequest $request) {
        $email = $request->email;
        
        if (User::where('email', $email)->doesntExist()) {
            return response([
                'message' => 'Invalid Email'
            ], 401);
        }
        
        // generate Random Token
        $token = rand(10, 100000);
        
        try {
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token
            ]);
            
            // Mail send to user
            Mail::to($email)->send(new ForgetMail($token));
             
            return response([
                'message' => 'Reset password email sent.'
            ], 200);
            
        } catch (Exception $exception) {
            return response([
                'message' => $exception->getMessage()], 400);
        }
    }

    public function reset(ResetRequest $request)
    {
        $email = $request->email;
        $token = $request->token;
        $password = Hash::make($request->password);

        // Check if email and pin exist in password_resets table
        $emailcheck = DB::table('password_resets')->where('email',$email)->first();
        $pincheck = DB::table('password_resets')->where('token',$token)->first();

        // Show error if email or pin don't exist
        if(!$emailcheck) {
            return response([
                'message' => "Email not found."
            ],401);
        }
        if(!$pincheck) {
            return response([
                'message' => "Invalid pin code."
            ],401);
        }

        // If they exist, update password and delete email from password_resets table
        DB::table('users')->where('email',$email)->update(['password'=>$password]);
        DB::table('password_resets')->where('email',$email)->delete();
        
        return response([
            'message' => 'Password changed succesfully.'
        ]);
    }
}
