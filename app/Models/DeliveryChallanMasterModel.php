<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryChallanMasterModel extends Model
{
    use HasFactory;

    protected $table='delivery_challan_master';
    protected $primaryKey = 'issue_no';
    
    protected $fillable = [
        'issue_no','return_issue_no','dc_case_id','issue_case_id','issue_date','return_date','product_type','sales_order_no','reciever_type','ac_code','otherBuyerorVendor','sent_through','dept_id','to_location',
        'tax_type_id','WashTypeId','total_qty','GrossAmount','GstAmount','NetAmount','narration','userId','delflag','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'issue_no' => 'string'
    ];
}
