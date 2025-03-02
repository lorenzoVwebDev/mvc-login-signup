<?php

class Signin_json {
  use Jasonwebtoken;
  private array $users_array = array ();
  private string $refreshToken = '';
  private string $users_data_json = '';
  private string $user_signin = '';
  private string $password_signin = '';

  function __construct(array $credentials) { 
    $this->user_signin = $credentials['username'];
    $this->password_signin = $credentials['password'];

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
      }
    } else {
      throw new Exception('applications.xml file not found', 500);
    }
  }

  function __destruct() {
    if ($this->refreshToken != '') {
      foreach ($this->users_array as &$user) {
        if ($user['username'] === $this->user_signin) {
          $user['refresh-token'] = $this->refreshToken;
          break;
        } 
      }
    }

    $encodedJson = json_encode($this->users_array);
    file_put_contents(__DIR__.$this->users_data_json, $encodedJson);
  }

  function userValidation() {
    foreach ($this->users_array as &$user) {
      if ($user['username'] === $this->user_signin) {
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