<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeDetailModel extends Model
{
    use HasFactory;

    protected $table='size_detail';
    protected $primaryKey = 'size_id';
	
	protected $fillable = [
      'sz_code', 'size_name'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

}
