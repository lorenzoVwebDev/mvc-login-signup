<?php
//this file will contain all the common controllers' functions

class Controller {
  use Jasonwebtoken;
  public function view($name) {
/*     $URL = $this->splitURL(); */
    //ucfirst is used to capitalize the firs letter of a string
    $filename = "../app/views/".$name.".view.php";
    if (file_exists($filename)) {
      if (($name ==='home') || ($name ==='signin') ) {
        require($filename);
      } else {  
        $accessToken = $this->requireAuth();
        ob_start();
        require($filename);
        $page = ob_get_clean();
        $response['access_token'] = $accessToken;
        $response['html'] = $page;
        echo json_encode($response);
      }
    } else {
      $filename = '../App/views/404.view.php';
      require($filename);
/*       throw new Exception($filename.'controller not found'); */
    }
  }
}