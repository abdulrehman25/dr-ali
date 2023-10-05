<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getUser($id){
       
        $user = User::find($id);
        if($user){
            return response(['data' =>$user, 'status' => '200']);
        }else{
            return response(['data' =>NULL, 'status' => '404']);
        }
    }

    public function updateUserProfile(Request $request){

        if (User::where('email', '=', $request->email)->exists()) {
            return response(["status" => "error",'data'=>'Email Already Exists']);

        } else {

            $user = User::find($request->id);
            if($user){
                dd($request->middle_name);
                $user->first_name = $request->first_name;
                $user->middle_name = $request->middle_name;
                $user->last_name = $request->last_name;
                $user->dob = $request->dob;
                //$user->email = $request->email;
                $user->past_medical_history = $request->past_medical_history;
                $user->phone = $request->phone;
                $user->save();
                return response(["status" => "200",'data'=>$user, 'message' => 'User Updated Successfully !']);
            }else{
                return response(["status" => "404",'data'=>'User not Exists']);
            }
        }
    }

    public function getUsersList(User $user){
        return response(['data'=>$user->getAllUser(),'status'=>200]);
    }

    public function deleteUser(Request $request, $id) {
        $user = User::find($id);
        if(is_null($user)) {
            return response()->json(['message' => 'User Not Found', 'status' => 404]);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted', 'status' => 200]);
    }
}
