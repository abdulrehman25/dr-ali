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

    public function getUserReport($user_id)
    {
        if($user_id > 0){
            $userReport = UserReport::where('user_id',$user_id)->get();
            if(count($userReport) == 0){
                return response(['status'=>404,'data'=>'No Record Found.']);
            }
            $arr = [];
        
            foreach($userReport as $key=>$report){
                if(in_array($report->title,$arr)){
                    $newArr[$report->title][] = public_path().'/user_reports/'.$report->report;
                }else{
                    $arr[] = $report->title;
                    $newArr[$report->title][] = public_path().'/user_reports/'.$report->report;

                }
            }
            
            return response(['status'=>200,'data'=>$newArr]);//[$newArr]
        }else{
            return response(['status'=>404,'data'=>'Please enter a valid user Id.']);
        }
        

    }
}
