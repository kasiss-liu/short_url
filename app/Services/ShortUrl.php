<?php
/**
 * 
 * @author kasiss
 * @date 2018-03-01
 * @description 短链接操作
 * 
 */
namespace App\Services;

use App\Models\SuRecord;

class ShortUrl {

  private $longUrl;
  public  $baseUrl;
  private $shortId;

  public function __construct(){
    $this->baseUrl = BASE_URL;
  }

  public function setUrl($longUrl="",$shortUrl="") {
    $this->longUrl = $longUrl;
    $this->shortId = $shortUrl;
    return $this;
  }

  /**
   * 生成短链接并保存
   */
  public function create() {
    //生成一个model 判断短链id是否存在
    $suModel = new SuRecord();
    //判断长链接是否保存
    $model = $suModel->searchLong($this->longUrl);
    if ($model) {
      return $model->id;
    }

    while(1) {
      //hash 获取一个短链的id
      $this->shortId = $this->hash();
       //如果id已存在 则重新生成一条
      if (!$suModel->checkExist($this->shortId)) {
          break;
      }
    }
     //保存短链
    $res = $suModel->save($this->shortId,$this->longUrl);
    if (!$res) {
      return false;
    }
    //返回数据
      return $this->shortId;
  }
  /**
   * 解析短链接
   */
  public function parse() {
    $suModel = new SuRecord();
    return $suModel->get($this->shortId);
  }
  //将长链转为短链的hash算法
  //sha1加密后 随机取6位连续字符
  //碰撞概率较大 适合量小的需求
  private function hash() {
      $hash = sha1($this->longUrl);
      return substr($hash,rand(0,34),6);
  }

}