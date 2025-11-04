<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleTransactionDetailModel extends Model
{
    use HasFactory;

    protected $table='sale_transaction_detail';

	
	protected $fillable = [
        'sr_no','sale_code','sale_date','buyer_po_no', 'Ac_code','sales_order_no','style_no_id','hsn_code','order_qty','pack_order_qty', 'unit_id','order_rate','disc_per','disc_amount','sale_cgst','camt','sale_sgst','samt','sale_igst','iamt','amount', 'total_amount','firm_id','created_at','updated_at',
    ];
	
// 	 protected $casts = [
//         'sale_code' => 'string'
//     ];
	
}
