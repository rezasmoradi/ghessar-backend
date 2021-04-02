<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportType extends Model
{
    use HasFactory, SoftDeletes;

    const REPORT_TYPE_USER = 'user';
    const REPORT_TYPE_TWIT = 'post';
    const REPORT_TYPE_COMMENT = 'comment';
    const REPORT_TYPES = [self::REPORT_TYPE_USER, self::REPORT_TYPE_TWIT, self::REPORT_TYPE_COMMENT];

    const IMPORTANCE_DEGREE_LOW = 'low';
    const IMPORTANCE_DEGREE_MEDIUM = 'medium';
    const IMPORTANCE_DEGREE_HIGH = 'high';
    const IMPORTANCE_DEGREE_VERY_HIGH = 'very high';
    const IMPORTANCE_DEGREES = [
        self::IMPORTANCE_DEGREE_LOW, self::IMPORTANCE_DEGREE_MEDIUM,
        self::IMPORTANCE_DEGREE_HIGH, self::IMPORTANCE_DEGREE_VERY_HIGH,
    ];
}
