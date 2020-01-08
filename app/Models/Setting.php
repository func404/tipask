<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    protected $table      = 'settings';
    protected $primaryKey = 'name';
    protected $fillable   = ['name', 'value'];
    public $timestamps    = false;

    public static function loadAll()
    {
        $settings = self::all();
        //  print_r($settings);
    }

    /*查询某个配置信息*/
    public static function get($name, $default = '')
    {
        $setting = self::where('name', '=', $name)->first();
        if ($setting) {
            return $setting->value;
        }
        return $default;
    }

    public static function set($name, $value)
    {
        self::updateOrCreate(['name' => $name], ['value' => $value]);
    }

    /**
     * 设置env配置文件
     * @param $params
     */
    public static function setEnvParams($params)
    {
        if (!$params) {
            return false;
        }
        // 为什么不用 env() 函数 呢,env（） 只能获取某个key的值
        $envPath   = app()->environmentFilePath(); //获取环境变量地址
        $envString = file_get_contents($envPath); //获取环境变量内容
        foreach ($params as $key => $value) {
            $envKey       = strtoupper($key);
            $oldValue     = env($envKey, null); // 默认值为null
            $keyString    = "{$envKey}=";
            $oldEnvString = "{$key}={$oldValue}";
            //判断如果环境变量的值包含空字符，那么oldEnvString 就设为当前环境变量
            if (str_contains($oldValue, ' ')) {
                $oldEnvString = "{$envKey}='$oldValue'";
            }
            $newEnvString = "{$envKey}=$value";
            //判断如果环境变量的值包含空字符，那么 newEnvString 就设参数为环境变量的值
            if (str_contains($value, ' ')) {
                $newEnvString = "{$envKey}='$value'";
            }
            if (str_contains($envString, $keyString)) {
                $envString = str_replace($oldEnvString, $newEnvString, $envString);
            } else {
                $envString .= $newEnvString . "\n";
            }
        }
        file_put_contents($envPath, $envString);
        return true;
    }

}
