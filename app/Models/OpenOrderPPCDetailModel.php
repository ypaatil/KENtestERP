<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class OpenOrderPPCDetailModel extends Model
{
    use HasFactory,LogsActivity;

    protected $table='open_order_ppc_details';
    protected $primaryKey = 'openOrderPPCDetailId';
	
	protected $fillable = [
        'sales_order_no', 'vendorId','vendorQty','userId','updated_at'
    ];

}
