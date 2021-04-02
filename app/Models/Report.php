<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reports';

    protected $fillable = [
        'reporter_id', 'reportable_id', 'reportable_type', 'reason_id'
    ];

    public function reportable()
    {
        return $this->morphTo();
    }
}
