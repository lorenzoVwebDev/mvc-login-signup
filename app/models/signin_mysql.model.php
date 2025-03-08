<?php

class Signin_mysql {
  use Jasonwebtoken;
  use Database;
  private array $users_array = array ();
  private string $refreshToken = '';
  private string $users_data_json = '';
  private string $user_signin = '';
  private string $password_signin = '';

  function __construct(array $credentials) { 
    $this->user_signin = $credentials['username'];
    $this->password_signin = $credentials['password'];

    $query_string = 'SELECT * FROM users';
    $array = $this->query($query_string);
    $this->users_array = $array;
  }

  function __destruct() {
    $mysqli = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBNAME);
    if (!$mysqli->query("DROP TABLE IF EXISTS users") || !$mysqli->query("CREATE TABLE IF NOT EXISTS users (
      username varchar(50),
      email varchar(255),
      password varchar(255),
      refresh_token varchar(255),
      datestamp integer,
      attempts integer not null CHECK (attempts >= 0 and attempts < 5),
      lastattempt integer,
      validattempt integer
    )")) {
      throw new Exception("Table can't be created or deleted".$mysqli->error, 500);
    }

    foreach ($this->users_array as $user) {
      $username = $user['username'];
      $email = $user['email'];
      $password = $user['password'];
      $refresh_token = $user['refresh_token'] ? $user['refresh_token'] : '';
      $datestamp = $user['datestamp'];
      $attempts = $user['attempts'];
      $lastattempt = $user['lastattempt'];
      $validattempt = $user['validattempt'];

      $query_string = "
      INSERT INTO users (username, email, password, refresh_token, datestamp, attempts, lastattempt, validattempt) VALUES (
        '$username',
        '$email',
        '$password',
        '$refresh_token',
        $datestamp,
        $attempts,
        $lastattempt,
        $validattempt
      )";

      if (!$mysqli->query($query_string)) {
        throw new Exception("Can't add new records: ". $mysql->error, 500);
      }
    }
  } 

  function userValidation() {
    foreach ($this->users_array as &$user) {

      if ($user['username'] === $this->user_signin || $user['email'] === $this->user_signin) {

        $currenttime = strtotime("now");
        $stamptime = $user['datestamp'];
        if ($currenttime>$stamptime) {
            
            $_SESSION['message'] = "changepassword";
            return false;
        } 
         

        if (($user['attempts'] < 3) || (strtotime('-5 minutes')>=$user['lastattempt'])) {
          $hash = $user['password'];
          if ((password_verify($this->password_signin, $hash))) {
            $_SESSION['username'] = $this->user_signin;
            $_SESSION['password'] = $this->password_signin;
            $user['validattempt'] = $currenttime;
            $user['lastattempt'] = $currenttime;
            $user['attempts'] = 0;
            $tokens = $this->generateTokens($_SESSION['username']); 
            $this->refreshToken = $tokens['refresh_token'];
            $user['refresh_token'] = $this->refreshToken;
            return $tokens;
          } else {
            $user['lastattempt'] = $currenttime;
            if (strtotime('-5 minutes')>=$user['lastattempt']) {
              $user['attempts'] = 1;
            } else if ($user['attempts'] < 3) {
              $user['attempts'] += 1;
            }
          }
        } else {
          $_SESSION['message'] = "passed";

          return false;
        }
      }
    }

    

    $_SESSION['message'] = "invalid";

    return false;
  }
}