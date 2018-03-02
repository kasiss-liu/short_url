<?php
/**
 * @author kasiss
 * @date 2018-03-01
 * @descritpion 
 *  方法入口，创建和解析短链接
 */
namespace App\Controllers;

use App\Services\ShortUrl as su;

class ShortUrl {

  //保存swoole分发的请求体和响应体
  private $req;
  private $resp;

  //加载swoole请求
  public function init($req,$resp){
    $this->req = $req;
    $this->resp = $resp;
    return $this;
  }
  /**
   * 执行创建方法
   */
  public function create() {
    $post = $this->req->post;
    $url = isset($post['url']) ? $post['url'] : "";
    //参数校验
    if (!$url) {
      return false;
    }
    //生成链接
    $su = new su();
    $shortId = $su->setUrl($url)->create();
    return $su->baseUrl.$shortId;
  }
  /**
   * 解析短链接
   */
  public function parse() {
    $shortId = trim($this->req->server["request_uri"],'/');
    $su = new su();
    $suModel = $su->setUrl("",$shortId)->parse();
    if ($suModel) {
      return $suModel->url;
    }
    return null;
  }

}