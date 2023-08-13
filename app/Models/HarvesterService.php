<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HarvesterService extends Model
{
    use HasFactory;
    protected $table = 'harvester_service_details';
    public $primaryKey = 'id';
    protected $guarded =[];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    public function ServicingType(){
        return $this->belongsTo(ServicingType::class,'servicing_type_id','id');
    }
    public function ProductModel(){
        return $this->belongsTo(ProductModel::class,'model_id','id');
    }
    public function MirrorProducts(){
        return $this->belongsTo(MirrorProduct::class,'ProductCode','ProductCode');
    }
}
