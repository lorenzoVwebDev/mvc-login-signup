<?php 
require __DIR__."//..//..//vendor//autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

trait Jasonwebtoken {
  private string $accessKey = JWT_ACCESS_KEY;
  private string $refreshKey = JWT_REFRESH_KEY;
  private string $algorithm = 'HS256';
  private int $accessTokenExpire = 3600;
  private int $refreshTokenExpire = 86000;

  public function generateTokens(string $username): array {
    $issuedAt = time();

    $accessToken = JWT::encode([
      'iss' => ROOT,
      'iat' => $issuedAt,
      'exp' => $issuedAt + $this->accessTokenExpire,
      'sub' => $username
    ], $this->accessKey, $this->algorithm);

    $refreshToken = JWT::encode([
      'iss' => ROOT,
      'iat' => $issuedAt,
      'exp' => $issuedAt + $this->refreshTokenExpire,
      'sub' => $username
    ], $this->refreshKey, $this->algorithm);

    return ['access_token' => $accessToken, 'refresh_token' =>$refreshToken];
  }

  private function verifyAccessToken(string $token): ?object {
    try {
      return JWT::decode($token, new Key($this->accessKey, $this->algorithm));
    } catch (Exception $e) {
      return null;
    }
  }

  private function verifyRefreshToken(string $token): ?object {
    try {
      return JWT::decode($token, new Key($this->refreshKey, $this->algorithm));
    } catch (Exception $e) {
      return null;
    }
  }

  public function requireAuth() {

    if (isset($headers['Authorization'])||isset($_SESSION['access_token'])||isset($_COOKIE['jwtRefresh'])) {
      if (isset($headers['Authorization'])) {
        $token = str_replace("Bearer ", '', $headers['Authorization']);
      } else if (isset($_SESSION['access_token'])) {
        $token = $_SESSION['access_token'];
      }

      $decoded = $this->verifyAccessToken($token);
    }
    
    if (!isset($decoded)) {
      $refreshToken = $_COOKIE['jwtRefresh'] ?? null;

      if (!$refreshToken) {
        http_response_code(401);
        echo json_encode(['error' => 'missing refresh token']);
        $URL = filter_var($_GET['url'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $_SESSION['URL'] = $URL;
        exit;
    }

      libxml_use_internal_errors(true);
      $xmlDoc = new DOMDocument();
    if (file_exists(__DIR__."//..//config//applications.xml")) {
      $xmlDoc->load(__DIR__."//..//config//applications.xml");

      $NodeArray = $xmlDoc->getElementsByTagName('type');

      foreach ($NodeArray as $nodeValue) {
        $value = $nodeValue->getAttribute('ID');
        if ($value === 'users_json_storage') {
          $xmlLocation = $nodeValue->getElementsByTagName('location');
          $users_data_json = $xmlLocation->item(0)->nodeValue;
        }
      }

      if (!file_exists(__DIR__.$users_data_json)) {
        throw new Exception('user.json is missing', 500);
      } 

      $jsonFile = file_get_contents(__DIR__.$users_data_json);
      $users_array = json_decode($jsonFile, true);
      $refreshTokenBool = false;

      foreach ($users_array as $user) {
        if ($user['refresh-token'] === $refreshToken) {
          $username = $user['username'];
          $refreshTokenBool = true;
          break;
        }
      }

      if (!$refreshTokenBool) {
        http_response_code(401);
        echo json_encode(['error' => 'missing refresh token']);
        $URL = filter_var($_GET['url'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $_SESSION['URL'] = $URL;
        exit;
      }

      $refreshDecoded = $this->verifyRefreshToken($refreshToken);
      
      if (!$refreshDecoded) {
        http_response_code(401);
        echo json_encode(['error' => 'refresh is expired']);
        exit;
      }

      $issuedAt = time();

      $accessToken = JWT::encode([
        'iss' => ROOT,
        'iat' => $issuedAt,
        'exp' => $issuedAt + $this->accessTokenExpire,
        'sub' => $username
      ], $this->accessKey, $this->algorithm);

      $URL = filter_var($_GET['url'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $_SESSION['URL'] = $URL;
      setcookie('jwtAccess', $accessToken, time()+10, '/', '', false, false);
      return true;
    }
  } else {
    return true;
  }
 }
}


