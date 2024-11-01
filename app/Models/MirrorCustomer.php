<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MirrorCustomer extends Model
{
    use HasFactory;

    protected $connection = 'MotorBrInvoiceMirror';

    protected $table = 'Customer';
    public $primaryKey = 'CustomerCode';
    protected $guarded = [];
    protected $keyType = 'string';

    public function mirror_district(){
        return $this->belongsTo('App\Models\MirrorDistrict','DistrictCode','DistrictCode')->select('DistrictCode','DistrictName');
    }
    public function mirror_upazilla(){
        return $this->belongsTo('App\Models\MirrorUpazila','ThanaCode','UpazillaCode')->select('UpazillaCode','UpazillaName');
    }
}
