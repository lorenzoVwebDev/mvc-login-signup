<?php

class Authentication extends Controller {

  public function authentication($name) {
    $this->view($name);
  } 

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
            if (isset($_SESSION['URL'])) {
              $_SESSION['access_token'] = $tokens['access_token'];
              $response['access_token'] = $tokens['access_token'];
              $response['requested-url'] = $_SESSION['URL'];
              http_response_code(200);
              header('Content-Type: application/json');
              setcookie('jwtRefresh', $tokens['refresh_token'], time()+86400, '/', '', false, false);
              echo json_encode($response);
            } else {
              $_SESSION['access_token'] = $tokens['access_token'];
              $token['access_token'] = $tokens['access_token'];
              http_response_code(200);
              header('Content-Type: application/json');
              setcookie('jwtRefresh', $tokens['refresh_token'], time()+86400, '/', '', false, false);
              echo json_encode($token);
            }
          } else if (array_key_exists('message', $tokens) && $tokens['message'] === 'changepassword') {
            $this->view('changepassword');
          }
        } else {
          throw new Exception('Missing credential\'s', 401);
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
        $response['result'] = 'Error 500, we are sorry! We are goin to fix that as soon as possible';
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

  public function signup() {
    try {
      if (isset($_POST['authentication'])) {
        if (isset($_POST['username'])||isset($_POST['password'])||isset($_POST['email'])) {
          $credentials['username'] = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
          $credentials['email'] = preg_match( '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', filter_var($_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) === 1 ? filter_var($_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : false;
          $credentials['password'] = preg_match( '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()])[A-Za-z\d!@#$%^&*()]{8,}$/', filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) === 1 ? filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : false;
          $type = filter_var($_POST['authentication'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
          if (in_array(false, $credentials)) {
            throw new Exception('Invalid email or password', 401);
          }
  
          $model = new Model();
          $signedup = $model->authentication($type, $credentials);

          if ($signedup === $_SESSION['message']) {
            switch ($signedup) {
              case 'user-created':
                http_response_code(200);
                $response['message'] = $signedup;
                echo json_encode($response);
                break;
              case 'existent-email':
                throw new Exception($signedup, 400);
                break;
              case 'existent-username':
                throw new Exception($signedup, 400);
                break;
            } 

          } else {
            throw new Exception('Session message not set', 500);
          }
          
        } else {
          throw new Exception('Missing sign-up credentials', 401);
        }
      } else {
        throw new Exception('Missing authentication type', 400);
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