<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageLockModel extends Model
{
    protected $table = "page_locks";

    protected $primaryKey = "pageLockId";

    protected $fillable = [
        'page_key',
        'userId',
        'locked_at',
        'isFlag',
        'tabId'
    ];

    public $timestamps = false;
}
