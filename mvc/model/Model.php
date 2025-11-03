<?php
// model/Model.php
include_once("model/Book.php");

class Model {

    // Dummy users: username => [password_hash, nama]
    private $users;

    public function __construct()
    {
        // buat beberapa dummy user; password as hash
        $this->users = [
            "glen" => [
                "password" => password_hash("password123", PASSWORD_DEFAULT),
                "nama" => "Glen Rejeki"
            ],
            "andi" => [
                "password" => password_hash("secret456", PASSWORD_DEFAULT),
                "nama" => "Andi Pratama"
            ]
        ];
    }

    // ambil data user (atau null)
    public function getUser($username)
    {
        if (isset($this->users[$username])) {
            return [
                "username" => $username,
                "password_hash" => $this->users[$username]["password"],
                "nama" => $this->users[$username]["nama"]
            ];
        }
        return null;
    }

    // authenticate: returns user array (username,nama) or false
    public function authenticate($username, $password)
    {
        $u = $this->getUser($username);
        if ($u && password_verify($password, $u["password_hash"])) {
            return ["username" => $u["username"], "nama" => $u["nama"]];
        }
        return false;
    }

    // Dummy book list
    public function getBookList()
    {
        return array(
            new Book("Jungle Book", "R. Kipling", "A classic adventure."),
            new Book("Moonwalker", "J. Walker", "Short essays about walking."),
            new Book("PHP for Dummies", "Some Smart Guy", "Intro to PHP.")
        );
    }

    public function getBook($title)
    {
        $all = $this->getBookList();
        foreach ($all as $b) {
            if ($b->title === $title) return $b;
        }
        return null;
    }
}
?>
