<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptModel extends Model
{
    use HasFactory;


        protected $table='transactions';
        protected $primaryKey='SrNo';   


	protected $fillable = [
    'TrType','TrNo','Date','ref_no','ref_date','UserId','DrCode','CrCode','Amount','Naration','c_code','created_at','updated_at',
    ];
    
    protected $attributes = [
        'delflag' => 0,
     ];
}
