<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricSummaryGRNMasterModel extends Model
{
    use HasFactory;

    protected $table='fabric_summary_grn_master';

    protected $primaryKey = 'sr_no';
	
	protected $fillable = [
        'fsg_code', 'fsg_date', 'po_code', 'po_type_id','chk_code', 'supplier_id', 'challan_no', 'challan_date', 'invoice_no', 'invoice_date',
        'transport_id', 'freight_paid', 'total_qty', 'narration', 'delflag', 'userId', 'created_at', 'updated_at' , 'c_code'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'fsg_code' => 'string'
    ];



}



?>