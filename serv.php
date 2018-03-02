<?php

/**
 * @author kasiss
 * @date 2018-02-08
 * @description  
 *  初始化常量、
 *  加载基础函数、
 *  封装一个swoole服务器类，
 *  启动服务
 */

// 定义几个路径常量
define(ROOT,realpath(__DIR__)); //根目录
define(APPPATH,ROOT."/app/"); //app
define(CONFIGPATH,APPPATH.'Config/'); //配置

require APPPATH."Bootstrap.php";
require APPPATH."Engine.php";

//启动服务
$serv = new Server();
$serv->start();



