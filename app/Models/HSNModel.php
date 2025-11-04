<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HSNModel extends Model
{
    use HasFactory;

    protected $table='hsn_master';
    protected $primaryKey = 'hsn_id';
	
	protected $fillable = [
        'cat_id','hsn_code', 'userId', 'created_at', 'updated_at' 
    ];

     
}
