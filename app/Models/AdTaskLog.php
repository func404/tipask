<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AdTaskLog extends BaseModel
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
        'platforms',
        'begin',
        'end',
        'remark',
        'real_amount',
        'discount',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * 平台信息
     * @dateTime 2020-01-09
     * @author Jingxinpo
     * @return   [type]     [description]
     */
    public function platform()
    {
        return $this->belongsTo(Platform::class, 'platforms', 'id');
    }
}
