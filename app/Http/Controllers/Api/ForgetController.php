<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\ForgetRequest;
use App\Mail\ForgetMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ForgetController extends Controller
{
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
}
