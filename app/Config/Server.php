<?php
/**
 * @author kasiss
 * @date 2018-02-08
 * @description 启动swoole所需要的参数
 */
return [
  'worker_num' => 4,
  'document_root' => 'public',
  'enable_static_handler' => true,
  'http_parse_post' => true,
  'addr' => '0.0.0.0',
  'port' => 8008
];