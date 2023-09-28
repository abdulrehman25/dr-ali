<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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


    public function saveAppointment($requestData, $id = '', $action)
    {
        $data = [];
        try {
            if ($action == 'add') {
                $this->insert(
                    [
                        'appointment_email' => $requestData['appointment_email'],
                        'name' => $requestData['name'],
                        'appointment_reason' => $requestData['appointment_reason'],
                        'appointment_date' => $requestData['appointment_date'],
                        'appointment_time' => $requestData['appointment_time'],
                        'appointment_number' => $requestData['appointment_number']
                    ]

                );
            } else {
                $this->where('id',$id)
                    ->update(
                        [
                            'name' => $requestData['name'],
                            'appointment_reason' => $requestData['appointment_reason'],
                            'appointment_email' => $requestData['appointment_email'],
                            'appointment_date' => $requestData['appointment_date'],
                            'appointment_time' => $requestData['appointment_time'],
                            'appointment_number' => $requestData['appointment_number']
                        ]

                    );
            }
            return $data = [
                'data' => $this,
                'status' => true
            ];

        } catch (Exception $e) {
            return $data = [
                'data' => 'Oops! Something went wrong. ' . $e->getMessage(),
                'status' => false
            ];
        }
    }
    public function getAppointmentById($id)
    {
        $data = [];
        try {
            $dataarr = $this->where('id', $id)
                ->first();
            return $data = [
                'data' => $dataarr,
                'status' => true
            ];

        } catch (Exception $e) {
            return $data = [
                'data' => 'Oops! Something went wrong. ' . $e->getMessage(),
                'status' => false
            ];
        }
    }
    public function deleteAppointmentById($id)
    {
        $data = [];
        try {
            $dataarr = $this->where('id', $id)
                ->delete();
            return $data = [
                'data' => $dataarr,
                'status' => true
            ];

        } catch (Exception $e) {
            return $data = [
                'data' => 'Oops! Something went wrong. ' . $e->getMessage(),
                'status' => false
            ];
        }
    }
    public function bookedAppointmentList($perPage=10)
    {
        $data = [];
        try {
            $dataarr = $this->paginate($perPage);
            return $data = [
                'data' => $dataarr,
                'status' => true
            ];

        } catch (Exception $e) {
            return $data = [
                'data' => 'Oops! Something went wrong. ' . $e->getMessage(),
                'status' => false
            ];
        }
    }
}