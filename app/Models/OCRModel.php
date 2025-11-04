<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OCRModel extends Model
{
    use HasFactory;

    protected $table='ocr_mater';
    protected $primaryKey = 'ocr_master_id';
	
	protected $fillable = [
    'sales_order_no','ocr_date', 'transport_qty', 'transport_image','testing_qty', 'testing_image', 'delflag', 'userId', 'created_at', 'updated_at' 
    ];

     
}
