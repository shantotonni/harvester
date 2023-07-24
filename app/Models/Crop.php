<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    use HasFactory;
    protected $table ='seasonal_crops';
    public $primaryKey ='id';
    protected $guarded = [];
    const CREATED_AT = 'created_at';
    const UPDATED_AT  = 'updated_at';
public function Crop(){
    return $this->hasMany(Crop::class,'seasonal_crops_id','id');
}
}
