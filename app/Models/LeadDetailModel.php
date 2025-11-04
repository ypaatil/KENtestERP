<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadDetailModel extends Model
{
    use HasFactory;
    protected $table='crm_details';
	protected $fillable = [
        'crm_id', 'contactName','contactNo', 'email'
    ]; 


}
