<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletSaleMasterModel extends Model
{
    use HasFactory; 

    protected $table='outlet_sale_master';
    protected $primaryKey = 'outlet_sale_id';
	
	protected $fillable = [
       'bill_date', 'bill_no','payment_option_id','employeeCode','other_customer','employee_type','scan_barcode','total_qty','gross_amount','total_disc_amount','total_gst_amount','net_amount','remark','mobile_no','gst_type','delflag', 'created_at', 'updated_at','userId'
    ];

    protected $attributes = [
        'delflag' => 0,
         
    ];



}
