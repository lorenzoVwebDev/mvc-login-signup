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

/*   public function __construct() {
    $this->accessKey = JWT_ACCESS_KEY;
    $this->refreshKey = JWT_REFRESH_KEY;
    $this->algorithm = 'HS256';
    $this->accessTokenExpire = 3600;
    $this->refreshTokenExpire = 604800;
  } */

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

  public function verifyAccessToken(string $token): ?object {
    try {
      return JWT::decode($token, new Key($this->accessKey, $this->algorithm));
    } catch (Exception $e) {
      return null;
    }
  }

  public function requireAuth(): ?object {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
      http_response_code(401);
      echo json_encode(['error' => 'unauthorized']);
      exit;
    }

    $token = str_replace("Bearer ", '', $headers['Authorization']);
    $decoded = $this->verifyAccessToken($token);
  }
}


/* $token = $_SERVER['HTTP_AUTHORIZATION'] ?? ''; // Get token from request

$decodedToken = $auth->verifyToken($token);

if ($decodedToken) {
    echo "Valid token. User ID: " . $decodedToken->sub;
} else {
    echo "Invalid or expired token!";
} */