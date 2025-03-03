<?php

class Signup_json {
  use Jasonwebtoken;
  private array $users_array = array ();
  private string $users_data_json = '';
  private string $user_signup = '';
  private string $email_signup = '';
  private string $password_signup = '';

  function __construct(array $credentials) { 
    $this->user_signup = $credentials['username'];
    $this->email_signup = $credentials['email'];
    $this->password_signup = $credentials['password'];

    show($this->password_signup);

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

  function userCreation() {
  }
}