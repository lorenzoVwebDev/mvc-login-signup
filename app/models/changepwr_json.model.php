<?php

class Changepwr_json {
  use Jasonwebtoken;
  private array $users_array = array ();
  private string $users_data_json = '';
  private string $user_changepwr = '';
  private string $old_password_changepwr = '';
  private string $new_password_changepwr = '';

  function __construct(array $credentials) { 
    $this->user_changepwr = $credentials['username'];
    $this->old_password_changepwr = $credentials['old-password'];
    $this->new_password_changepwr = $credentials['new-password'];

    libxml_use_internal_errors(true);
    $xmlDoc = new DOMDocument();
    if (file_exists(__DIR__."//..//config//applications.xml")) {
      $xmlDoc->load(__DIR__."//..//config//applications.xml");

      $NodeArray = $xmlDoc->getElementsByTagName('type');

      foreach ($NodeArray as $nodeValue) {
        $value = $nodeValue->getAttribute('ID');
        if ($value === 'users_json_storage') {
          $xmlLocation = $nodeValue->getElementsByTagName('location');
          $this->users_data_json = $xmlLocation->item(0)->nodeValue;
        }
      }
      if (file_exists(__DIR__.$this->users_data_json)) {
        $jsonFile = file_get_contents(__DIR__.$this->users_data_json);
        $this->users_array = json_decode($jsonFile, true);
      } else {
        throw new Exception('user.json file not found', 500);
      }
    } else {
      throw new Exception('applications.xml file not found', 500);
    }
  }

  function __destruct() {
    $encodedJson = json_encode($this->users_array);
    file_put_contents(__DIR__.$this->users_data_json, $encodedJson);
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