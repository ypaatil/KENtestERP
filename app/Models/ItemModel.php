<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemModel extends Model
{
    use HasFactory;

      protected $table='item_master';
      protected $primaryKey = 'item_code';
	
	protected $fillable = [
    'cat_id','class_id', 'material_type_id', 'item_name','quality_code', 'item_description', 'color_name', 'unit_id', 'dimension', 'item_image_path', 'moq', 'item_rate', 'item_mrp',
    'cgst_per', 'sgst_per', 'igst_per', 'hsn_code', 'pur_rate', 'sale_rate', 'userId',  'created_at', 'updated_at' 
    ];

     
}
