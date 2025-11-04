<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricCheckStatusModel extends Model
{
    use HasFactory;
    
    protected $table='fabric_check_status_master';
    protected $primaryKey = 'fcs_id';
	
	protected $fillable = [
        'fcs_name', 'userId', 'created_at', 'updated_at' 
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

}
