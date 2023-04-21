<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
class BookAppointment extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'name',
        'visit_type',
        'appointment_reason',
        'appointment_date',
        'appointment_time',
        'appointment_email',
        'appointment_number',
    ];
    public $rules = [
        'visit_type' => 'required',
        'name' => 'required',
        'appointment_reason'=>'required',
        'appointment_date'=>'required|date|date_format:Y-m-d',
        'appointment_time'=>'required|date_format:H:i',
        'appointment_email'=>'required',
        'appointment_number'=>'required|numeric|digits:10',
    ];

    public function saveAppointment($request){
      
        $validator = Validator::make($request->all(),$this->rules);
        if ($validator->fails()) {
            $response = $validator->messages();
            return response(["data" => $response, 'status' =>'error']);
        }else{
            $this->name = $request->name;
            $this->visit_type = $request->visit_type;
            $this->appointment_reason = $request->appointment_reason;
            $this->appointment_date = $request->appointment_date;
            $this->appointment_time = $request->appointment_time;
            $this->appointment_email = $request->appointment_email;
            $this->appointment_number = $request->appointment_number;
            $this->save();
            $response=$this;
        }

        return response(["data" => $response,'status'=>'success']);
    }
}
