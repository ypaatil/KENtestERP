<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadModel extends Model
{
    use HasFactory;

    protected $table='crm_master';

    protected $primaryKey = 'crm_id';
	
	protected $fillable = [
         'crm_id', 'buyer_name', 'buyer_brand', 'buyer_type_id', 'order_group_id','state_id','city_id','zip_code','street_name','stage_id','lead_status_id','compliant','cur_id','ownership_name','userId', 'delflag', 'created_at', 'updated_at'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

}
