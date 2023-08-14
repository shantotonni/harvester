<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCard extends Model
{
    use HasFactory;

    protected $table = 'job_cards';
    public $primaryKey = 'id';
    protected $guarded =[];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function model(){
        return $this->belongsTo(ProductModel::class,'model_id','id');
    }
    public function products(){
        return $this->belongsTo(Products::class,'product_id','id');
    }
    public function district(){
        return $this->belongsTo(District::class,'district_id','id');
    }
    public function upazila(){
        return $this->belongsTo(Upazila::class,'upazila_id','id');
    }
    public function section(){
        return $this->belongsTo(Section::class,'section_id','id');
    }
    public function technician(){
        return $this->belongsTo(Technician::class,'technitian_id','id');
    }




    public function territory(){
        return $this->belongsTo('App\Models\Territory');
    }
    public function area(){
        return $this->belongsTo('App\Models\Area','area_id','id');
    }
    public function engineer(){
        return $this->belongsTo('App\Models\User','engineer_id','id');
    }
    public function technitian(){
        return $this->belongsTo('App\Models\User','technitian_id','id');
    }
    public function participant(){
        return $this->belongsTo('App\Models\User');
    }
    public function call_type(){
        return $this->belongsTo('App\Models\CallType');
    }
    public function customer(){
        return $this->belongsTo('App\Models\Customer','customer_id','id');
    }
       public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function image(){
        return $this->belongsTo('App\Models\ChassisImage','job_card_id','id');
    }
    public function service_types(){
        return $this->belongsTo(ServiceType::class,'service_type_id','id');
    }




}
