<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Package extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
    ];
    public function savePackage($requestData, $id = '', $action)
    {
        
        $data = [];
        try {
            if ($action == 'add') {
                $this->insert(
                    [                        
                        'name' => $requestData['name'],
                        'price' => $requestData['price']
                    ]

                );
            } else {
                $this->where('id',$id)
                    ->update(
                        [
                            'name' => $requestData['name'],
                            'price' => $requestData['price']
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
    public function deleteAPackageById($id)
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
    public function packageList($perPage=10)
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
