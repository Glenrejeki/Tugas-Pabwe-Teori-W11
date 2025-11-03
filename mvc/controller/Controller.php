<?php
// controller/Controller.php
include_once("model/Model.php");

class Controller {
    public $model;

    public function __construct()
    {
        session_start();
        $this->model = new Model();
    }

    public function invoke()
    {
        // route param: ?route=home | login | logout | book=Title
        $route = isset($_GET['route']) ? $_GET['route'] : 'home';

        // login form submitted?
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            // minimal sanitasi; authenticate via model
            $user = $this->model->authenticate($username, $password);
            if ($user) {
                // set session
                $_SESSION['user'] = $user;
                header("Location: index.php?route=home");
                exit;
            } else {
                $error = "Invalid username or password.";
                include 'view/login.php';
                return;
            }
        }

        // logout
        if ($route === 'logout') {
            session_unset();
            session_destroy();
            header("Location: index.php?route=login");
            exit;
        }

        // routing
        if ($route === 'login') {
            include 'view/login.php';
        } elseif ($route === 'home') {
            // require login to see personalized home
            $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
            if (!$user) {
                header("Location: index.php?route=login");
                exit;
            }
            // get books
            $books = $this->model->getBookList();
            include 'view/home.php';
        } elseif ($route === 'book' && isset($_GET['book'])) {
            $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
            if (!$user) { header("Location: index.php?route=login"); exit; }
            $book = $this->model->getBookByTitle($_GET['book']);
            if ($book) include 'view/viewbook.php';
            else echo "Book not found.";
        } else {
            echo "Unknown route.";
        }
    }
}
?>
