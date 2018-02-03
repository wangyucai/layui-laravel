<?php

namespace App\Handlers;

use Image;

class MoreFilesUploadHandler
{
    protected $allowed_ext = ["png", "jpg", "gif", 'jpeg'];

    public function save($files, $file, $totalPieces, $index, $folder)
    {
        $originalName = $file->getClientOriginalName(); // 文件原名,
        $progress = round(($index/$totalPieces),2)*100;
        if($index == ($totalPieces - 1)){
            $progress = 100;  //进度条
        }
        $folder_name = "uploads/$folder/files/" . date("Ym", time()) . '/'.date("d", time()).'/';
        $upload_path = public_path() . '/' . $folder_name;
        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';
        // 拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID
        $filename = time() . '_' . str_random(10) . '.' . $extension;
        $savePath = $upload_path.$filename;
         // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $filename);
        return  [
                    'info' => '上传成功!',
                    'tolink' =>  config('app.url') . "/$folder_name".$filename,
                    'imgid' => $filename,
                    'code'      => 0,
                    'progress' => $progress,
                    'originalName' => $originalName,
                    'size' => $files['size'],
        ];
    }
}