<?php
// 代理请求并返回结果
// 跨域支持
header('Access-Control-Allow-Origin: *');



echo  file_get_contents($_GET['url']);

?>