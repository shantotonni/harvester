<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table ='districts';
    public $primaryKey='id';
    protected $guarded = [];
    const CREATED_AT = 'created_at';
    const UPDATED_AT  = 'updated_at';
    public function District()
    {
        return $this->hasMany(District::class,'district_id','id');

    }

}
