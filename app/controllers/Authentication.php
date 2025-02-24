<?php

class Authentication extends Controller {

  public function signin() {
    try {
      if (isset($_POST['username'])&&isset($_POST['password'])) {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  
        $credentials['username'] = $username;
        $credentials['password'] = $password;
  
        $model = new Model();
        $model->authentication('sign-in', $credentials);
  
  
      } else {
        throw new Exception('Missing credential/s', 401);
      }
    } catch (Exception $e) {
      if ($e->getCode() >= 400 && $e->getCode() < 500) {
        http_response_code((int) $e->getCode());
        header('Content-Type: application/json');
        $response['result'] = $e->getMessage();
        $response['status'] = $e->getCode();
        require_once(__DIR__ ."//..//models//logs.model.php");
        $exception = new Logs_model($e->getMessage()." ".$e->getFile(), 'exception');
        $last_log_message = $exception->logException();
        unset($exception);
      } else {
        http_response_code(500);
        header('Content-Type: application/json');
        $response['result'] = 'We are sorry! We are goin to fix that as soon as possible';
        $response['status'] = 500;
/*         echo json_encode($response); */
        require_once(__DIR__ ."//..//models//logs.model.php");
        $exception = new Logs_model($e->getMessage()." ".(string)$e->getFile(), 'exception');
        $last_log_message = $exception->logException();
        unset($exception);
      }
    }
  }
}