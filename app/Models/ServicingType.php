<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicingType extends Model
{
    use HasFactory;
    protected $table = 'servicing_types';
    public $primaryKey = 'id';
    protected $guarded =[];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function ServicingType(){
        return $this->hasMany(ServicingType::class,'servicing_type_id','id');
    }
}
