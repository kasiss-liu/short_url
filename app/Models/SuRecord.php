<?php
/**
 * @author kasiss
 * @date 2018-03-01
 * @description 
 *  mongodb 操作模型
 */

namespace App\Models;

class SuRecord {

    private $id;
    private $url;
    private $createTime;

    private $manager;
    private $dbName;
    private $coName = "urls";
    private $collection;

    public function __construct() {
        $this->manager = new \MongoDB\Driver\Manager("mongodb://".HOST.":".PORT."/");   
        $this->dbName = DBNAME;
    }
    /**
     * 验证短链id是否存在
     */
    public function checkExist($id) {
      $query = new \MongoDB\Driver\Query(["id"=>$id],[]);
      $rows = $this->manager->executeQuery("{$this->dbName}.{$this->coName}",$query);
      foreach($rows as $row) {
        return true;
      }
      return false;
    }
    /**
     * 保存短链接数据
     */
    public function save($id,$url){
      $this->id = $id;
      $this->url = $url;
      $this->createTime = date("Y-m-d H:i:s");

      $bulk = new \MongoDB\Driver\BulkWrite;
      $document = ["id"=>$id,"url"=>$url,"create_time"=>$this->createTime];
      $bulk->insert($document);

      return $this->manager->executeBulkWrite("{$this->dbName}.{$this->coName}", $bulk);
    }
    /**
     * 获取链接数据
     */
    public function get($id) {
      $query = new \MongoDB\Driver\Query(["id"=>$id],[]);
      $rows = $this->manager->executeQuery("{$this->dbName}.{$this->coName}",$query);
      foreach($rows as $row) {
        return $row;
      }
      return null;
    }
    /**
     * 根据长链接搜索短链接
     */
    public function searchLong($url) {
      $query = new \MongoDB\Driver\Query(["url"=>$url],[]);
      $rows = $this->manager->executeQuery("{$this->dbName}.{$this->coName}",$query);
      foreach($rows as $row) {
        return $row;
      }
      return null;
    }
}