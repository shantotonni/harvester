<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;
    protected $table = 'job_cards';
    public $primaryKey = 'id';
    protected $guarded=[];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    public function section(){
        return $this->belongsTo(Section::class,'section_id','id');
    }
    public function District()
    {
        return $this->belongsTo(District::class,'district_id','id');

    }
    public function upazila(){
        return $this->belongsTo('App\Models\Upazila');
    }
    public function ProductModel(){
        return $this->belongsTo(ProductModel::class,'model_id','id');
    }
    public function Products(){
        return $this->belongsTo(Products::class,'product_id','id');
    }
    public function Customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
    public function service_type(){
        return $this->belongsTo('App\ServiceType','service_type_id','id');
    }
    public function approver(){
        return $this->belongsTo('App\User');
    }
    public function territory(){
        return $this->belongsTo(Territory::class,'territory_id','id');
    }
    public function area(){
        return $this->belongsTo(Area::class,'area_id','id');
    }
    public function engineer(){
        return $this->belongsTo('','engineer_id','id');
    }

    public function technitian(){
        return $this->belongsTo('App\Models\Technician','technitian_id','id');
    }
    public function participant(){
        return $this->belongsTo('App\Models\User');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function image(){
        return $this->hasMany('App\Models\ChassisImage','service_request_id','id');
    }




}
