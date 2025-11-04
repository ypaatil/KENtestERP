<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportViewerModel extends Model
{
    use HasFactory;

    protected $table='report_viewer';
    protected $primaryKey = 'reportViewerId';
	
	protected $fillable = [
        'moduleId','form_code','description','userId'
    ];
}

