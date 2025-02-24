<?php

class Signin_json {
  private array $users_array = array ();
  private string $users_data_json = '';

  function __construct() {
    libxml_use_internal_errors(true);
    $xmlDoc = new DOMDocument();
    if (file_exists(__DIR__."//..//config//applications.xml")) {
      $xmlDoc->load(__DIR__."//..//config//applications.xml");
    } else {
      throw new Exception('applications.xml file not found', 500);
    }
  }
}