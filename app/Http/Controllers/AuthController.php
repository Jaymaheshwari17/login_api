<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * User login api using sanctum
     * 
     */

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email or Password does not match.'
                ], 403);
            }

            if ($user->status !== '1') {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is inactive. Please contact admin.'
                ], 403);
            }

            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user
            ], 200);
        } catch (\Throwable $th) {
           return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
