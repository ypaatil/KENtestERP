<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletSaleDetailModel extends Model
{
    use HasFactory; 

    protected $table='outlet_sale_detail';
	
	protected $fillable = [
       'outlet_sale_id', 'bill_date','bill_no','scan_barcode','product_id','product_name','style_no','qty','size_id','stock_qty','rate','amount','discount','discount_amount','gst_per','gst_amount','total_amount','brand_id'
    ];

}
