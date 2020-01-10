<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 父类 模型
 * @author   Michael Liang    <liang15946@163.com>
 * @date     2018-11-10
 */
class BaseModel extends Model {
    // 不自动维护 created_at 和 updated_at 字段
    public $timestamps = false;
    // 默认主键
    public $primaryKey = 'id';
    // 默认数据库连接
    protected $connection = 'mysql';
}