<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelPump extends Model
{
    use HasFactory;
    protected $table = 'fuel_pump';
    public $primaryKey = 'fuel_pump_id';
    protected $guarded =[];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function District()
    {
        return $this->belongsTo(District::class,'district_id','id');

    }
}
