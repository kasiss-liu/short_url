<?php
/**
 * @author kasiss
 * @date 2018-02-28
 * @description 定义一些全局函数
 */

//一个全局获取配置数据的函数
function getConfig($config) {
  $file = CONFIGPATH.$config.'.php';
  if(file_exists($file)) {
    return include($file);
  }
  throw new Exception("$config lost");
}

//自动加载函数
function __autoload($className){
  $splits = explode("\\",$className);
  unset($splits[0]);
  $file = APPPATH.implode("/",$splits).".php";
  if(file_exists($file)) {
    include_once($file);
    return;
  }
  return false;
}
//初始部分配置位全局常量
$appConfig = getConfig("App");
foreach($appConfig as $key=>$val) {
  !defined(strtoupper($key)) && define(strtoupper($key),$val);
}
$dbConfig = getConfig("Db");
foreach($dbConfig as $key => $val) {
  !defined(strtoupper($key)) && define(strtoupper($key),$val);
}
