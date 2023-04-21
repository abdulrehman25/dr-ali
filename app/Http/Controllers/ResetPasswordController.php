<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'code' => 'required|exists:reset_code_passwords',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if($validator->fails()) {
            $response = $validator->messages();
            return $response;
        }

        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        if ($passwordReset->isExpire()) {
            return response(['message' => trans('passwords.code_is_expire'), 'status' => 422] );
        }

        $user = User::firstWhere('email', $passwordReset->email);

        $user->update(['password' => Hash::make($request->password)]);

        $passwordReset->delete();

        return response(['message' =>'password has been successfully reset', 'status' => 200]);
    }

    public function edit(Request $request){
        return response(['message' =>'password has been successfully reset'], 200);
    }
}
