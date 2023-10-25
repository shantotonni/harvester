<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'customers';
    public $primaryKey = 'id';
    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function Customer_chassis(){
        return $this->hasOne('App\Models\CustomerChassis','customer_id','id');
    }
    public function ProductModel(){
        return $this->belongsTo(ProductModel::class,'model_id','id');
    }
    public function Products(){
        return $this->belongsTo(Products::class,'product_id','id');
    }
    public function District()
    {
        return $this->belongsTo(District::class,'district_id','id');

    }
    public function area(){
        return $this->belongsTo(Area::class,'area_id','id');
    }
}
