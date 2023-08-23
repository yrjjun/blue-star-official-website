<?php
$zip = new ZipArchive;
if ($zip->open('program.zip') === TRUE) {
    $zip->extractTo('./'); // 解压到当前目录
    $zip->close();
    echo '程序更新完成！';
} else {
    echo '无法打开程序压缩包';
}
