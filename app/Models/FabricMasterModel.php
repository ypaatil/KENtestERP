<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricMasterModel extends Model
{
    use HasFactory;

      protected $table='purchase_fabric_master';
      protected $primaryKey = 'sr_no';

	protected $fillable = [
   'fpur_code','fpur_date','cp_id','tax_type_id','fpur_bill','Ac_code','add1','add2','less1','less2','total_meter','total_qty','gross_amount','cgst_per','cgst_amt','sgst_per','sgst_amt','igst_per','igst_amt','gst_amount','net_amount','narration','UserId','CounterId','firm_id','Purchase_Ac_code','created_at','updated_at',
    ];

    protected $attributes = [
        'showflag' => 0,
     ];

}