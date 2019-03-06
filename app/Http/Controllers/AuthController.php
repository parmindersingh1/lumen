<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Validator;
use App\User;

class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public  function  register(Request  $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return  response()->json($validator->errors(), 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => app('hash')->make($request->password),
        ]);
        // $token = auth()->login($user);
        $credentials = $request->only(['email', 'password']);
        $token = $this->jwt->attempt($credentials);
        return  response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->jwt->factory()->getTTL() * 60
            ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return  response()->json($validator->errors(), 422);
        }
        $credentials = $request->only(['email', 'password']);
        if (!$token = $this->jwt->attempt($credentials)) {
            return  response()->json(['error' => 'Invalid
             Credentials'], 400);
        }
        $current_user = $request->email;
            return  response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'current_user' => $current_user,
            'expires_in' => $this->jwt->factory()->getTTL() * 60
            ], 200);
    }

    public  function  logout(Request  $request){
        $token = $request->header('Authorization');
       
        try {
            $this->jwt->invalidate($token);
            return response()->json(['success' => 'Logged out Successfully.'], 200);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'Failed to logout, please try again.'], 500);
        }
         
    }
    
}