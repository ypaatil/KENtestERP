<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterNumberModel extends Model
{
    use HasFactory;
    protected $table='counter_number';
    protected $primaryKey = 'c_code';
	
	protected $fillable = [
        'c_name', 'tr_no', 'type', 'CBarcode','PBarcode', 'tr_code',
    ];
 
}
