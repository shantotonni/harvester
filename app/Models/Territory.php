<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Territory extends Model
{
    use HasFactory;

    protected $table = 'territories';
    public $primaryKey = 'id';
    protected $guarded =[];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function area(){
        return $this->belongsTo('App\Models\Area');
    }

}
