<?php
session_start();

class User {

    public $username;
    public $password;
    public $auth = false;

    public function __construct() {

    }

    public function test () {
      $db = db_connect();
      $statement = $db->prepare("select * from users;");
      $statement->execute();
      $rows = $statement->fetch(PDO::FETCH_ASSOC);
      return $rows;
    }

    public function authenticate($username, $password) {
    $username = strtolower($username);
        if (empty($username) || empty($password)) {
            $_SESSION["loginError"] = 'All fields are required';
            header("Location: /login");
            die();
        }
        
        $db = db_connect();
        $statement = $db->prepare("select * from users WHERE username = :name;");
        $statement->bindValue(':name', $username);
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);
        
        if (password_verify($password, $rows['password'])) {
          $_SESSION['auth'] = 1;
          $_SESSION['username'] = ucwords($username);
          unset($_SESSION['failedAuth']);
          unset($_SESSION['loginError']);
          header('Location: /home');
          die;
        } else {
          if(isset($_SESSION['failedAuth'])) {
            $_SESSION['failedAuth'] ++; //increment
          } else {
            $_SESSION['failedAuth'] = 1;
          }
        $_SESSION["loginError"] = 'Invalid username or password.';
        header('Location: /login');
          die;
        }
  }

  public function create($username, $password, $verifypassword) {

      $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/';
      // Check if fields are empty
      if (empty($username) || empty($password) || empty($verifypassword)) {
        $_SESSION["createError"] = 'All fields are required';
        header("Location: /create");
        die;
      }
      // Check if passwords match
      if ($password !== $verifypassword) {
        $_SESSION["createError"] = 'Passwords do not match';
        header("Location: /create");
        die;
      }
      // Validate password pattern
      if (!preg_match($passwordPattern, $password)) {
        $_SESSION["createError"] = 'Password must contain at least 8 characters, including one uppercase letter, one lowercase letter, one number, and one special character.';
        header("Location: /create");
        die;
      }
      // Database connection
      $db = db_connect();
      // Check if username exists
      $statement = $db->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
      $statement->execute([$username]);
      if ($statement->fetchColumn() > 0) {
        // Username exists
        $_SESSION["createError"] = 'Username already exists';
        header("Location: /create");
        die;
      }
      // Hash the password and add user to db
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $statement = $db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
      if ($statement->execute([$username, $hashed_password])) {
        $_SESSION["auth"] = 1;
        $_SESSION["username"] = $username; 
        header("Location: /home");
        die;
      } else {
        $statement->close();
        $db->close();
        $_SESSION["createError"] = 'Registration failed, please try again';
        header("Location: /create");
        die;
      }
  }
}
?>