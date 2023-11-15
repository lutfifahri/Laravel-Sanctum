<?php

namespace App\Http\Controllers;

use App\Models\role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class authController extends Controller
{
   public function registerUser(Request $request)
   {
        $datauser = new User();
        $rules = [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'proses validasi gagal',
                'data'      =>  $validator->errors() 
            ], 401);
        }

        $datauser->name = $request->name;
        $datauser->email = $request->email;
        $datauser->password = Hash::make($request->password);
        $datauser->save();

        return response()->json([
            'status'    => true,
            'message'   => 'Data Berhasil Disimpan' 
        ], 200);
   }

   public function loginUser(Request $request)
   {
        $rules = [
            'email'     => 'required|email',
            'password'  => 'required', 
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'proses login gagal',
                'data'      => $validator->errors()
            ], 401);
        }

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status'    => false,
                'message'   => 'email dan password yang dimasukan tidak sesuai'
            ], 401);
        }

        $datauser = User::where('email', $request->email)->first();
        $role = role::join("user_role", "user_role.role_id", "=", "roles.id")
            ->join("users", "users.id", "=", "user_role.user_id")
            ->where('user_id', $datauser->id)
            ->pluck('roles.role_name')->toArray();

            if (empty($role)) {
                $role = ["*"];
            }

        return response()->json([
            'status'    => true,
            'message'   => 'berhasil proses login',
            'token'     => $datauser->createToken('api-product', $role)->plainTextToken
        ]);
   }
}
