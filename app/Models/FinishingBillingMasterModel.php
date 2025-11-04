<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishingBillingMasterModel extends Model
{
    use HasFactory;

    protected $table='finishing_billing_master';

    protected $primaryKey = 'finishing_billing_code';
	
	protected $fillable = [
         'finishing_billing_code', 'finishing_billing_date', 'perticular_id', 'bill_no', 'supplier_id', 'total_qty','total_amount','narration', 'userId', 'delflag', 'created_at', 'updated_at'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

}
