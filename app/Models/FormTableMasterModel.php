<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormTableMasterModel extends Model
{
    use HasFactory;

    protected $table='year_end_form_table_master';
    protected $primaryKey = 'form_id';
	
	protected $fillable = [
         'form_name', 'form_detail', 'userId', 'seq_no', 'created_at', 'updated_by', 'updated_at'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
