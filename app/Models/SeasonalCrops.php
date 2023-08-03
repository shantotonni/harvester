<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonalCrops extends Model
{
    use HasFactory;
    protected $table ='district_wise_seasonal_crops';
    public $primaryKey='id';
    protected $guarded = [];

    public function Crop(){
        return $this->belongsTo(Crop::class,'seasonal_crops_id','id');
    }
    public function District()
    {
        return $this->belongsTo(District::class,'district_id','id');

    }
}
