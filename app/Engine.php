<?php
/**
 * @author kasiss
 * @date 2018-03-02
 * @description
 *  服务器引擎
 */

//一个Swoole服务器的封装
class Server {
  private $listen; //可用访问源
  private $port; //端口
  private $config; //服务器配置
  private $routes; //服务器内路由

  private $http; //服务器实例

  //初始化一些服务器的配置
  public function __construct(){
    //生成实例
    $this->config = getConfig("Server");
    $allow = $this->config['addr'];
    $port = $this->config['port'];
    $this->http = new \Swoole\Http\Server($allow,$port);
    //对实例注册配置
    $serverConfig = [
      "worker_num" => $this->config['work_num'],
      'document_root' => ROOT.'/'.$this->config['document_root'],
      'enable_static_handler' => $this->config['enable_static_handler'],
      'http_parse_post' => $this->config['http_parse_post'],
    ];  
    $this->http->set($serverConfig);
  }
  public function init() {
    //注册请求函数
    $this->http->on("request",[$this,'route']);
     //注册路由
     $file = APPPATH."Routes/Route.php";
     $this->routes = include_once($file);
  }
  //请求回调函数
  public function route($req,$resp) {
    //判断是不是创建短链接的请求
    $func = $this->getRoute($req->server['request_uri']);
    if ($func) {
      try {
        $app = (new $func[0]())->init($req,$resp);
        $responseMessage = call_user_func([$app,$func[1]]);
        $resp->end($responseMessage);
      }catch(Exception $e) {
        $resp->status(500);
        $resp->end("Bad GateWay");
      }
      return;
    }
    //短链跳转
    $func = $this->getRoute("dispatch");
    try{
      $app = (new $func[0]())->init($req,$resp);
      $url = call_user_func([$app,$func[1]]);
      if($url) {
        $resp->status(302);
        $resp->header("Location",$url);
        $resp->end("");
        return;
      }
    }catch(Exception $e) {
      $resp->status(500);
      $resp->end("Bad GateWay");
    }
    //未获取到链接的话 报404
    $resp->status(404);
    $resp->end("Page Not Found! ");
  }
  //获取路由
  public function getRoute($path) {
    if(isset($this->routes[$path])) {
      $func = explode("@",$this->routes[$path]);
      if(count($func)==2) {
        return $func;
      }
    }
    return false;
  }
  //启动服务
  public function start() {
    $this->init();
    $this->http->start();
  }
}