<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpareItemModel extends Model
{
    use HasFactory;

      protected $table='spare_item_master';
      protected $primaryKey = 'spare_item_code';
	
	protected $fillable = [
    'cat_id','class_id', 'item_name', 'item_description', 'mc_model_id','machinetype_id','mc_make_Id','unit_id', 'dimension',
    'cgst_per', 'sgst_per', 'igst_per', 'hsn_code', 'min_qty', 'userId', 'created_at', 'updated_at' 
    ];

    protected $attributes = [
        'delflag' => 0,
    ];
}
