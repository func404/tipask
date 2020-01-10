<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AdTask extends BaseModel
{
    use SoftDeletes;
    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * Fields that can be mass assigned.
     *
     * @var array
     */
    public $fillable = [
        'task_name',
        'disable',
        'user_id',
        'ad_task_log_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // 正在使用的记录
    public function log()
    {
        return $this->hasOne(AdTaskLog::class, 'id', 'ad_task_log_id');
    }

    // 所有记录
    public function logs()
    {
        return $this->hasMany(AdTaskLog::class, 'ad_task_id', 'id');
    }

    //明细
    public function details()
    {
        return $this->hasMany(AdTaskDetail::class, 'ad_task_id', 'id');
    }

    // 手机号
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
