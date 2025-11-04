<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionDetailModel extends Model
{
    use HasFactory;
 
protected $table='requisition_detail';

	protected $fillable = [
        'requisitionNo','requisitionDate','item_code','unit_id','requestedQty','stockQty','approvedQty','firm_id','created_at', 'updated_at'
    ];

    protected $casts = [
        'requisitionNo'=>'string'
    ];


}
