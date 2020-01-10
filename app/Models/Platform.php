<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\SoftDeletes;

class Platform extends BaseModel
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
        'name',
        'host',
        'disable',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        // 'deleted_at',
    ];

    /**
     * 生成select下拉选择框
     * @param $categories
     * @param int $parentId
     * @param int $depth
     * @return string
     */
    public static function makeOptionTree($platforms, $selectId = 0, $depth = 0)
    {
        $childTree = '';
        foreach ($platforms as $platform) {

            if ($platform->id == $selectId) {
                $childTree .= "<option value=\"{$platform->id}\" selected>";
            } else {
                $childTree .= "<option value=\"{$platform->id}\">";
            }
            $depthStr = str_repeat("--", $depth);
            $childTree .= $depth ? "&nbsp;&nbsp;|{$depthStr}&nbsp;{$platform->name}</option>" : "{$platform->name}</option>";
            // $childTree .= self::makeOptionTree($categories, $selectId, $category->id, $depth + 1);

        }
        return $childTree;
    }
}
