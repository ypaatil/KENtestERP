<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerCostingAttachementModel extends Model
{
    use HasFactory;

    protected $table='buyer_costing_attachments';
 
	protected $fillable = [
        'sr_no', 'attachment_name', 'attachment_image','updated_at','created_at'
    ]; 

}
