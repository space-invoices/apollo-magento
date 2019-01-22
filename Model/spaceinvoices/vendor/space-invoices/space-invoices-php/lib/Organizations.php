<?php

namespace Spaceinvoices;

class Organizations extends ApiResource {
  const path = "organizations";

  use ApiOperations\GetById;

  /**
   * @param string $accountId ID of Account
   * @param object $data
   *
   * @return object Returns data of created Organization
  */
  public static function create($accountId, $data) {
    return parent::_POST("/accounts/".$accountId."/".static::path, $data)->body;
  }

  /**
   * @param string $accountId ID of Account
   *
   * @return object Returns list of Organizations
  */
  public static function find($accountId) {
    return parent::_GET("/accounts/".$accountId."/".static::path)->body;
  }

}