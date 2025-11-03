<?php
// model/Model.php
class Model {
    private $pdo;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // simple env loader (or use phpdotenv)
        $env = function($k,$d=null){
            if (getenv($k) !== false) return getenv($k);
            if (file_exists(__DIR__ . '/../.env')) {
                $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos(trim($line),'#')===0) continue;
                    if (!strpos($line,'=')) continue;
                    list($name,$val) = explode('=', $line, 2);
                    if ($name === $k) return trim($val);
                }
            }
            return $d;
        };

        $host = $env('DB_HOST','127.0.0.1');
        $port = $env('DB_PORT','5432');
        $db   = $env('DB_DATABASE','pabwe_mvc');
        $user = $env('DB_USERNAME','postgres');
        $pass = $env('DB_PASSWORD','');

        $dsn = "pgsql:host={$host};port={$port};dbname={$db};";
        $this->pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    }

    // get user by username (returns assoc or false)
    public function getUserByUsername($username)
    {
        $sql = "SELECT id, username, password_hash, nama FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: false;
    }

    // authenticate
    public function authenticate($username, $password)
    {
        $user = $this->getUserByUsername($username);
        if (!$user) return false;
        // use password_verify (bcrypt/argon2)
        if (password_verify($password, $user['password_hash'])) {
            // regenerate session id after login (prevent session fixation)
            session_regenerate_id(true);
            // return minimal public user info
            return ['id'=>$user['id'],'username'=>$user['username'],'nama'=>$user['nama']];
        }
        return false;
    }

    // books functions
    public function getBookList()
    {
        $sql = "SELECT id,title,author,description FROM books ORDER BY id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookByTitle($title)
    {
        $sql = "SELECT id,title,author,description FROM books WHERE title = :title LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':title' => $title]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // helper untuk menambahkan user (dipakai hanya oleh admin/seeder)
    public function createUser($username, $password, $nama)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username,password_hash,nama) VALUES (:u,:ph,:n)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':u'=>$username,':ph'=>$hash,':n'=>$nama]);
        return $this->pdo->lastInsertId();
    }
}
?>
