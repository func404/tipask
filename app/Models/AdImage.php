<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AdImage extends BaseModel
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
        'url',
        'name',
        'disable',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function makeOptionTree($images, $selectId = 0, $depth = null)
    {
        $childTree = '';
        foreach ($images as $image) {

            if ($image->id == $selectId) {
                $childTree .= "<option value=\"{$image->id}\" selected>";
            } else {
                $childTree .= "<option value=\"{$image->id}\">";
            }
            $depthStr = str_repeat("--", $depth);
            $childTree .= $depth ? "&nbsp;&nbsp;|{$depthStr}&nbsp;{$image->name}</option>" : "{$image->name}</option>";
            // $childTree .= self::makeOptionTree($categories, $selectId, $category->id, $depth + 1);

        }
        return $childTree;
    }
}
