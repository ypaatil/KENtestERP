<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryChallanDetailModel extends Model
{
    use HasFactory;
    protected $table='delivery_challan_detail';
    protected $primaryKey = 'dc_detail_id';
    
    protected $fillable = [
       'issue_no','return_issue_no','item_description','unit_id','quantity','return_quantity','rate','total_amount','remark'
    ];
    protected $casts = [
        'issue_no' => 'string'
    ];
}