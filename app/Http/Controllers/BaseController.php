<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

/**
 * @author   荆新坡   <492971588@qq.com>
 * @date     2018-7-30
 * @desc     控制器 基类
 * @version  1.0
 */
class BaseController extends Controller
{
    // 返回json
    public function toJson($code, $data = null, $msg = '')
    {
        $result = [
            'code' => $code,
            'msg'  => trans('msg.' . $code),
        ];

        if ($msg) {
            $result['msg'] = $msg;
        }

        if ($data) {
            $result['data'] = $data;
        }

        return response()->json($result);
    }

    /**
     * 导出execl
     * @dateTime 2020-01-08
     * @author Jingxinpo
     * @param    [type]     $callback  [description]
     * @param    string     $file_name [description]
     * @return   [type]                [description]
     */
    public function excel($callback, $file_name = '')
    {
        set_time_limit(0);

        if (empty($file_name)) {
            $file_name .= 'default_' . date('Ymd') . '.xlsx';
        } else {
            // $file_name = urlencode($file_name);
            $file_name .= '.xlsx';
        }

        // header('Content-disposition: attachment; filename*="utf8\'\'' . $file_name . '"');
        header('Content-disposition: attachment;filename="' . $file_name . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        $writer = new \XLSXWriter();
        $writer->setAuthor('未来鲜森');
        call_user_func($callback, $writer);
        $writer->writeToStdOut();
        exit;

    }

    /**
     * 导出csv
     * @return [type] [description]
     */
    public function csv($callback, $file_name = '')
    {
        set_time_limit(0);

        if (empty($file_name)) {
            $file_name .= 'default_' . date('Ymd') . '.csv';
            // $file_name .= 'default_' . date('Ymd');
        } else {
            // $file_name = urlencode($file_name);
            $file_name .= date('Ymd') . '.csv';
            // $file_name .= date('Ymd');
        }

        // Header("Content-type:  application/octet-stream ");
        header('Content-Type: application/vnd.ms-excel');
        header('Cache-Control: max-age=0');
        header('Content-Disposition: attachment;filename="' . $file_name . '"');
        $fp = fopen('php://output', 'a');
        call_user_func($callback, $fp);

        ob_flush(); // 清除缓冲区
        flush(); //清除php buffer
        ob_end_clean(); //手动结束脚本，数据发送给客户端浏览器
        exit();
    }

}
