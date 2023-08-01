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
    public function product(){
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

}
