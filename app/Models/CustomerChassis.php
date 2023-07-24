<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerChassis extends Model
{
    use HasFactory;
    protected $table ='customer_chassis';
    public $primaryKey='id';
    protected $guarded = [];
    const CREATED_AT = 'created_at';
    const UPDATED_AT  = 'updated_at';

    public function Customer(){
        return $this->belongsTo(Crop::class,'customer_id','id');
    }
}
