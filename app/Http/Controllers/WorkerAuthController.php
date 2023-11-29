<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Worker;
use Validator;

class WorkerAuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:worker', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (!$token = auth()->guard('worker')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:workers',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:17',
            'photo' => 'required|image|mimes:jpg,png,jpeg',
            'location' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $worker = Worker::create(array_merge(
            $validator->validated(),
            [
                'password' => bcrypt($request->password),
                'photo' => $request->file('photo')->store('workers'),
            ]
        ));
        return response()->json([
            'message' => 'Worker successfully registered',
            'worker' => $worker
        ], 201);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'worker successfully signed out']);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile()
    {
        return response()->json(auth()->guard('worker')->user());
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'worker' => auth()->guard('worker')->user()
        ]);
    }
}
