<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $table = 'areas';
    public $primaryKey = 'id';
    protected $guarded =[];
    public function area(){
        return $this->hasMany(Area::class,'area_id','id');
    }
}
