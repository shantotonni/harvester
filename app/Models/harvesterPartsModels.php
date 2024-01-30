<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class harvesterPartsModels extends Model
{
    use HasFactory;
    protected  $table = 'harvester_parts_models';
    public $primaryKey = 'id';
    protected $guarded=[];

    public function HarvesterParts(){
        return $this->belongsTo(HarvesterParts::class,'parts_id','parts_id');
    }
}
