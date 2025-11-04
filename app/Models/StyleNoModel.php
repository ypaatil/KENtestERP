<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StyleNoModel extends Model
{
    use HasFactory;
    protected $table='style_no_master';
    protected $primaryKey = 'style_no_id';
	protected $fillable = [
        'style_no', 'userId','delflag','created_at','updated_at','Ac_code'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
