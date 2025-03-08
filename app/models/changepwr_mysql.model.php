<?php

class Changepwr_json {
  use Database;
  private array $users_array = array ();
  private string $users_data_json = '';
  private string $user_changepwr = '';
  private string $old_password_changepwr = '';
  private string $new_password_changepwr = '';

  function __construct(array $credentials) { 
    $this->user_changepwr = $credentials['username'];
    $this->old_password_changepwr = $credentials['old-password'];
    $this->new_password_changepwr = $credentials['new-password'];

    $query_string = 'SELECT * FROM mvc_login_signup';
    $array = $this->query($query_string);
    show($array);
  }

  function __destruct() {
/*     $encodedJson = json_encode($this->users_array);
    file_put_contents(__DIR__.$this->users_data_json, $encodedJson); */
  }

  function changePassword() {
    foreach ($this->users_array as &$user) {
      if (in_array($this->user_changepwr, $user)) {
        $hash = $user['password'];
        if (password_verify($this->old_password_changepwr, $hash)) {
          $user['password'] = password_hash($this->new_password_changepwr, PASSWORD_DEFAULT);
          $user['datestamp'] = strtotime("now");
          $user['attempts'] = 0;
          $_SESSION['message'] = 'pwr-changed';
          return true;
        } else {
          $_SESSION['message'] = 'invalid-password';
          return false;
        }
      }
    }
    $_SESSION['message'] = 'user-not-found';
    return false;
  }
}