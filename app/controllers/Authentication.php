<?php

class Authentication extends Controller {

  public function signin() {
    try {
      if (isset($_POST['authentication'])) {
        if (isset($_POST['username'])&&isset($_POST['password'])) {
          $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
          $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
          $type = filter_var($_POST['authentication'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
          $credentials['username'] = $username;
          $credentials['password'] = $password;
    
          $model = new Model();
          $tokens = $model->authentication($type, $credentials);
          if (array_key_exists('access_token', $tokens) && array_key_exists('refresh_token', $tokens)) {
            $token['access_token'] = $tokens['access_token'];
            http_response_code(200);
            header('Content-Type: application/json');
            setcookie('jwtRefresh', $tokens['refresh_token'], time()+604800, '/', '', false, false);
            echo json_encode($token);
          } else {
            throw new Exception('$_SESSION["username"] and $_SESSION["password"] have not been set correctly with the user\'s username and password');
          }
        } else {
          throw new Exception('Missing credential/s', 401);
        }
      } else {
        throw new Exception('wrong request', 400);
      }
    } catch (Exception $e) {
      if ($e->getCode() >= 400 && $e->getCode() < 500) {
        http_response_code((int) $e->getCode());
        header('Content-Type: application/json');
        $response['result'] = $e->getMessage();
        $response['status'] = $e->getCode();
        require_once(__DIR__ ."//..//models//logs.model.php");
        $exception = new Logs_model($e->getMessage()." ".(string) $e->getFile(), 'exception');
        $last_log_message = $exception->logException();
        unset($exception);
        echo json_encode($response);
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
        echo json_encode($response);
      }
    }
  }
}