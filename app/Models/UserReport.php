<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class UserReport extends Model
{
    use HasFactory;
    protected $fillable=['title','report'];

    public $rules = [
        'title' => 'required',
        'user_id' => 'required',
        'report' => 'required|max:2048'
    ];

    public function saveUserReport($request){
        $validator = Validator::make($request->all(),$this->rules);
        if ($validator->fails()) {
            $response = $validator->messages();
            return response(["data" => $response, 'status' =>'error']);
        }else{
            $profileReport = NULL;
            if ($report = $request->file('report')) {
                $destinationPath = 'user_reports/';
                $profileReport = date('YmdHis').$report->getClientOriginalName();
                $report->move($destinationPath, $profileReport);
            }
            $this->user_id = $request->user_id;
            $this->title = $request->title;
            $this->report = $profileReport;
            $this->save();
            $response=$this;
        }

        return response(["data" => $response,'status'=>'success']);
    }
}
