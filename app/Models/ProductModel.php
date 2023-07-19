<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;
    protected $table = 'product_model';
    public $primaryKey = 'id';
    protected $guarded =[];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function ProductModel(){
        return $this->hasMany(ProductModel::class,'model_id','id');
    }
    public function Products(){
        return $this->belongsTo(Products::class,'product_id','id');
    }
}
