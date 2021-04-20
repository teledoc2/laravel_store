<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function index(Request $request){
        $role = $request->role;
        $authUser = User::find(Auth::id());
        if( $authUser->role("manager") && $role != "driver" ){
            return response()->json([
                "message" => "Not Authrized for the request",
            ], 401);
        }

        if( !$authUser->hasAnyRole("manager","admin") ){
            return response()->json([
                "message" => "Not Authrized for the request",
            ], 401);
        }

        $users = User::when($role, function($query) use ($role){
            return $query->role($role);
        })->get();

        return response()->json([
            "data" => $users,
        ], 200);
    }
}
