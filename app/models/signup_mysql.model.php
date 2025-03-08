<?php

class Signup_json {
  use Jasonwebtoken;
  use Database;
  private array $users_array = array ();
  private string $users_data_json = '';
  private string $user_signup = '';
  private string $email_signup = '';
  private string $password_signup = '';

  function __construct(array $credentials) { 
    $this->user_signup = $credentials['username'];
    $this->email_signup = $credentials['email'];
    $this->password_signup = $credentials['password'];

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
      lastattempt integer DEFAULT NULL,
      validattempt integer DEFAULT NULL
    )")) {
      throw new Exception("Table can't be created or deleted".$mysqli->error, 500);
    }

    foreach ($this->users_array as $user) {

      $username = $user['username'];
      $email = $user['email'];
      $password = $user['password'];
      $datestamp = $user['datestamp'];
      $attempts = $user['attempts'];
      $lastattempt = $user['lastattempt'];
      $validattempt = $user['validattempt'];

      $query_string = "
      INSERT INTO users (username, email, password, refresh_token, datestamp, attempts) VALUES (
        '$username',
        '$email',
        '$password',
        '',
        $datestamp,
        $attempts
      )";

      if (!$mysqli->query($query_string)) {
        throw new Exception("Can't add new records: ". $mysql->error, 500);
      }
    }
  }

  function userCreation() {
    foreach ($this->users_array as &$user) {
      if ($user['username'] === $this->user_signup) {
        $_SESSION['message'] = 'existent-username';
        return false;
      } else if ($user['email'] === $this->email_signup) {
        $_SESSION['message'] = 'existent-email';
        return false;
      }
    }
    $hash = password_hash($this->password_signup, PASSWORD_DEFAULT);

    $newUser['username'] = $this->user_signup;
    $newUser['email'] = $this->email_signup;
    $newUser['password'] = $hash;
    $newUser['datestamp'] = strtotime("now + 30 days");
    $newUser['attempts'] = 0;
    $newUser['lastattempt'] = "NULL";
    $newUser['validattempt'] = "NULL";

    $this->users_array[] = $newUser;

    return $_SESSION['message'] = 'user-created';
    return true;
  }
}