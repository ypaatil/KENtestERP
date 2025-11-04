<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonModel extends Model
{
    use HasFactory;

    protected $table='season_master';
    protected $primaryKey = 'season_id';
	
	protected $fillable = [
        'Ac_code', 'season_name','description', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

}
