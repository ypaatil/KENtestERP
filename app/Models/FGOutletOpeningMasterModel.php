<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FGOutletOpeningMasterModel extends Model
{
    use HasFactory;

    protected $table='fg_outlet_opening_master';

    protected $primaryKey = 'fgo_code';
	
	protected $fillable = [
         'fgo_code', 'fgo_date', 'total_qty', 'narration', 'userId', 'delflag', 'created_at', 'updated_at','c_code', 'is_opening', 'sz_code'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'fgo_code' => 'string'
    ];



}
