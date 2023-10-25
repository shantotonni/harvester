<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    use HasFactory;
    protected $table = 'dealer_centers';
    public $primaryKey = 'id';
    protected $guarded =[];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function area()
    {
        return $this->belongsTo(Area::class,'area_id','id');

    }
}
