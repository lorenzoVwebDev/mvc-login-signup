<?php

class Controller {
  use Jasonwebtoken;
  public function view($name) {
    $filename = "../app/views/".$name.".view.php";
    if (file_exists($filename)) {

      if (($name ==='home') || ($name ==='signin') || ($name === 'signup') || ($name === '500') || ($name === 'changepwr')) {
        require($filename);
      } else {  
        $auth = $this->requireAuth();
        if ($auth) {
          header('Content-Type: text/html');
          ob_start();
          require_once($filename);
          $buffer = ob_get_clean();
          echo $buffer;
        } else {

        }
      }
    } else {
      $filename = '../App/views/404.view.php';
      require($filename);
    }
  }

}