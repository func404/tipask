<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AdTaskDetail extends BaseModel
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
        'ad_task_id',
        'platform_id',
        'ad_task_position_id',
        'ad_image_id',
        'ad_image_url',
        'disable',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * 去吧
     * @dateTime 2020-01-09
     * @author Jingxinpo
     * @return   [type]     [description]
     */
    public function task()
    {
        return $this->belongsTo(AdTask::class, 'ad_task_id', 'id');
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class, 'platform_id', 'id');
    }

    public function position($value = '')
    {
        return $this->belongsTo(AdPosition::class, 'ad_task_position_id', 'id');
    }
}
