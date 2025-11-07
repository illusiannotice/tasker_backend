<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UsersController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email:rfc,dns',
            'password' => 'required|string|regex:^(?=.*[0-9])(?=.*[!@#$%^&*()_+\-=\[\]{};:\\|,.<>\/?]).{8,}$',
            'password_repetition' => 'required|string|regex:^(?=.*[0-9])(?=.*[!@#$%^&*()_+\-=\[\]{};:\\|,.<>\/?]).{8,}$'
        ]);
        if($validated['password'] == $validated['password_repetition']){
            $user = User::create($validated);
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'user' => $user,
                'token' => $token,
                'msg' => 'success'
            ]);
        }
    }
    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email:rfc,dns',
            'password' => 'required|string|regex:^(?=.*[0-9])(?=.*[!@#$%^&*()_+\-=\[\]{};:\\|,.<>\/?]).{8,}$'
        ]);
        
        
        if(Auth::attempt($validated)){
            $user = User::where('email', $request->email)->firsOrFail();

            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json([
                'user' => $user,
                'token'=> $token,
                'msg' => 'success'
            ]);
        }
        return response()->json([
            'page' => 'login_page',
            'msg' => 'failed',
        ]);
    }
    public function getUser(Request $request){
    
        return response()->json($request->user());
    }
    
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
    }
}
