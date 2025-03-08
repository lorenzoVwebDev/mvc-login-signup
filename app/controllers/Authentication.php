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
            $this->view('changepwr');
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

        require_once(__DIR__ ."//..//models//logs.model.php");
        $exception = new Logs_model($e->getMessage()." ".(string)$e->getFile(), 'exception');
        $last_log_message = $exception->logException();
        unset($exception);
        echo json_encode($response);
      }
    }
  }

  public function logout($delete) {
    try {
      if ($delete === 'access'&&$_SERVER['REQUEST_METHOD'] === 'DELETE') {

        if (isset($_COOKIE['jwtRefresh'])) {
          setcookie("jwtRefresh", "", time() - 3600);
        }
    
        if (isset($_SESSION['access_token'])) {
          unset($_SESSION['access_token']);
        }
    
        if (!(isset($_SESSION['access_token']))) {
          http_response_code(200);
          $response['message'] = "log out";
          echo json_encode($response);
        } else {
          throw new Exception('jwtRefresh cookie or access token not deleted', 500);
        }
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
        require_once(__DIR__ ."//..//models//logs.model.php");
        $exception = new Logs_model($e->getMessage()." ".(string)$e->getFile(), 'exception');
        $last_log_message = $exception->logException();
        unset($exception);
        echo json_encode($response);
      }
    }
  }

  public function changepwr() {
    try {
      if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $form_json = file_get_contents('php://input', true);
        $form = json_decode($form_json, true);
        if (isset($form['username']) && isset($form['authentication'])) {
          $credentials['username'] = filter_var($form['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
          $type = filter_var($form['authentication'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
          if (isset($form['old-password'])) {
            $credentials['old-password'] = filter_var($form['old-password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (isset($form['new-password']) && isset($form['confirm-new-password'])) {
              $credentials['new-password'] = filter_var($form['new-password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
              $credentials['confirm-new-password'] = filter_var($form['confirm-new-password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
              if ($credentials['new-password'] === $credentials['confirm-new-password']) {
                if ($type === 'change-password') {
                  $model = new Model();
                  $changepwr = $model->authentication($type, $credentials);
                  unset($model);
                  if ($changepwr === $_SESSION['message']) {
                    switch ($changepwr) {
                      case 'pwr-changed':
                        require_once(__DIR__ ."//..//models//logs.model.php");
                        $changeLog = new Logs_model($credentials['username']." ".$changepwr, 'access');
                        $changeLog->logAccess();
                        http_response_code(200);
                        $response['message'] = 'Password has been changed';
                        echo json_encode($response);
                        break;
                      case 'invalid-password':
                        throw new Exception('Invalid password', 401);
                        break;
                      case 'user-not-found':
                        throw new Exception('User not found', 401);
                        break;
                    }
                  }
                } else {
                  throw new Exception('wrong authentication type', 401);
                }
              } else {  
                throw new Exception('confirmation password is different from new password', 403);
              }
            } else {
              throw new Exception('missing new password', 401);
            }
          } else {
            throw new Exception('missing old-password', 401);
          }
        } else {
          throw new Exception('missing username', 401);
        }

      } else {
        throw new Exception('invalid request method', 400);
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
        require_once(__DIR__ ."//..//models//logs.model.php");
        $exception = new Logs_model($e->getMessage()." ".(string)$e->getFile(), 'exception');
        $last_log_message = $exception->logException();
        unset($exception);
        echo json_encode($response);
      }
    }
  }

}
