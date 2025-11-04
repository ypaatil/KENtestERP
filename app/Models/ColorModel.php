<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ColorModel extends Model
{
    use HasFactory;
    protected $table='color_master';
    protected $primaryKey = 'color_id';
	protected $fillable = [
        'color_name', 'style_img_path','status', 'userId','delflag','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
