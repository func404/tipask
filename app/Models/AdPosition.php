<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\SoftDeletes;

class AdPosition extends BaseModel
{
    // use SoftDeletes;
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
        'platform_id',
        'describe',
        'mark',
        'disable',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * 广告位关联的平台
     * @dateTime 2020-01-09
     * @author Jingxinpo
     * @return   [type]     [description]
     */
    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    // 获取所关联的广告
    public function taskDetails()
    {
        return $this->hasMany(AdTaskDetail::class, 'ad_task_position_id', 'id');
    }

    /**
     * 生成多选框
     * @param $categories
     * @param int $parentId
     * @param int $depth
     * @return string
     */
    public static function makeOptionTree($positions, $task_details = null)
    {
        $str = '';
        foreach ($positions as $position) {
            $checked = false;
            // 有效任务明细
            if ($task_details && (!$task_details->isEmpty()) && in_array($position->id, $task_details)) {
                $checked = true;
            }

            if ($checked) {
                $str .= '<label><input type="checkbox" name="positions[]" value="' . $position->id . '"' . 'checked />' . $position->mark . '</label>';
            } else {
                $str .= '<label><input type="checkbox" name="positions[]" value="' . $position->id . '"' . '/>' . $position->mark . '</label>';
            }
        }
        return $str;
    }
}
