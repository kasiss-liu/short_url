<?php
/**
 * @author kasiss
 * @date 2018-02-28
 * @description 注册路由对应的执行方法
 */
return [
  "/shorturl/create" => "App\Controllers\ShortUrl@create",
  "dispatch"=>"App\Controllers\ShortUrl@parse"
];