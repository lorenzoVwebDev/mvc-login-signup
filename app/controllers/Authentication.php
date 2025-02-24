<?php

class Authentication extends Controller {

  public function signin() {
    if (isset($_POST['username'])&&isset($_POST['password'])) {
      $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      
    }
  }
}