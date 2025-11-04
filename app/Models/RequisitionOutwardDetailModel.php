<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionOutwardDetailModel extends Model
{
    use HasFactory;

    protected $table='requisition_outward_detail';

	protected $fillable = [
        'requisition_outward_no','requisition_outward_date','requisitionNo','item_code','unit_id','balanceQty','approvedQty','issuedQty','firm_id','created_at', 'updated_at'
    ];

    protected $casts = [
        'requisition_outward_no'=>'string'
    ];
}
