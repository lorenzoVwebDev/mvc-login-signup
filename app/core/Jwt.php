<?php 
require __DIR__."//..//..//vendor//autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

trait Jasonwebtoken {
  private string $accessKey = JWT_ACCESS_KEY;
  private string $refreshKey = JWT_REFRESH_KEY;
  private string $algorithm = 'HS256';
  private int $accessTokenExpire = 3600;
  private int $refreshTokenExpire = 604800;

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
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
      http_response_code(401);
      echo json_encode(['error' => 'unauthorized']);
      header('Location: '.ROOT."public/");
      exit;
    }

    $token = str_replace("Bearer ", '', $headers['Authorization']);
    $decoded = $this->verifyAccessToken($token);

    if (!$decoded) {
      $refreshToken = $_COOKIE['jwtRefresh'] ?? null;

      if (!$refreshToken) {
        http_response_code(401);
        echo json_encode(['error' => 'missing refresh token']);
        header('Location: '.ROOT."public/");
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
        header('Location: '.ROOT."//public//");
        exit;
      }

      $refreshDecoded = $this->verifyRefreshToken($refreshToken);
      
      if (!$refreshDecoded) {
        http_response_code(401);
        echo json_encode(['error' => 'refresh is expired']);
        header('Location: '.ROOT."//public//");
        exit;
      }

      $issuedAt = time();

      $accessToken = JWT::encode([
        'iss' => ROOT,
        'iat' => $issuedAt,
        'exp' => $issuedAt + $this->accessTokenExpire,
        'sub' => $username
      ], $this->accessKey, $this->algorithm);

      header('Content-Type: application/json');
      echo json_encode(['access_token' => $accessToken]);
      $URL = $_GET['url'];
    }
    }
  }
}


/* $token = $_SERVER['HTTP_AUTHORIZATION'] ?? ''; // Get token from request

$decodedToken = $auth->verifyToken($token);

if ($decodedToken) {
    echo "Valid token. User ID: " . $decodedToken->sub;
} else {
    echo "Invalid or expired token!";
} */

