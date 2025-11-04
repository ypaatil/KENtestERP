<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormTableDetailModel extends Model
{
    use HasFactory;

    protected $table='year_end_form_table_detail'; 
	
	protected $fillable = [
       'sr_no', 'form_id', 'last_year_database_name', 'new_year_database_name', 'table_name', 'p_key_name'
    ];
 
}
