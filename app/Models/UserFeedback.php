<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class UserFeedback extends Model
{
    use HasFactory;
    protected $table = 'users_feedback';
    protected $fillable =['user_id','first_name','last_name','email','image','rating','feedback'];

    public $rules = [
        'email' => 'required|email',
        'rating' => 'required',
        'feedback' => 'required',
        'first_name' => 'required',
        'last_name' => 'required',
        'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
    ];

    public function saveUserFeedBack($request){
       
        $validator = Validator::make($request->all(),$this->rules);
        if ($validator->fails()) {
            $response = $validator->messages();
            return response(["data" => $response, 'status' =>'error']);
        }else{
            if ($image = $request->file('image')) {
                $destinationPath = 'image/';
                $profileImage = date('YmdHis').$image->getClientOriginalName();
                $image->move($destinationPath, $profileImage);
            }
            // $image_path = $request->file('image')->store('image', 'public');
            $this->user_id = $request->user_id;
            $this->first_name = $request->first_name;
            $this->last_name = $request->last_name;
            $this->feedback = $request->feedback;
            $this->email = $request->email;
            $this->rating = $request->rating;
            $this->image = $profileImage??'';
            $this->save();
            $response=$this;
        }

        return response(["data" => $response,'status'=>'success']);
    }


}
