<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorWorkOrderSewingTrimsDetailModel extends Model
{
    use HasFactory;


    protected $table='vendor_work_order_sewing_trims_details';
 
	protected $fillable = [
        'vw_code', 'vw_date', 'cost_type_id', 'Ac_code', 'sales_order_no', 'season_id', 'currency_id',
        'item_code', 'class_id', 'description',    'consumption', 'unit_id',
          'wastage', 'bom_qty'  , 'final_cons','actual_qty', 'size_qty'
    ];

    protected $casts = [
        'vw_code' => 'string'
    ];
}
