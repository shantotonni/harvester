<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HarvesterParts extends Model
{
    use HasFactory;

    use HasFactory;
    protected  $table = 'harvester_parts';
    public $primaryKey = 'parts_id';
    protected $guarded=[];
    const CREATED_AT = 'created_at';
    const UPDATED_AT  = 'updated_at';

    public function ProductModel(){
        return $this->belongsTo(ProductModel::class,'product_model_id','id');
    }
    public function MirrorProducts(){
        return $this->belongsTo(MirrorProduct::class,'ProductCode','ProductCode');
    }
    public function section(){
        return $this->belongsTo(Section::class,'section_id','id');
    }
}
