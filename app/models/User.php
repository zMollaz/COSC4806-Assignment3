<?php
session_start();

class User {

    public function __construct() {
        // Constructor code (if any)
    }

    public function test() {
        $db = db_connect();
        $statement = $db->prepare("SELECT * FROM users;");
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function authenticate($username, $password) {
        $username = strtolower($username);
        if (empty($username) || empty($password)) {
            $_SESSION["loginError"] = 'All fields are required';
            header("Location: /login");
            die;
        }

        $db = db_connect();

        // Check if the user is locked out by querying the logs table
        $statement = $db->prepare("SELECT COUNT(*) AS failed_attempts 
                                   FROM logs 
                                   WHERE username = :name AND attempt = 'bad' AND time > NOW() - INTERVAL 60 SECOND");
        $statement->bindValue(':name', $username);
        $statement->execute();
        $user_attempts = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user_attempts['failed_attempts'] >= 3) {
            $_SESSION["loginError"] = 'Account locked. Try again after 60 seconds.';
            header('Location: /login');
            die;
        }

        $statement = $db->prepare("SELECT * FROM users WHERE username = :name;");
        $statement->bindValue(':name', $username);
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $rows['password'])) {
            $_SESSION['auth'] = 1;
            $_SESSION['username'] = ucwords($username);
            unset($_SESSION['loginError']);

            // Log successful attempt
            $attempt = 'good';
            $time = date('Y-m-d H:i:s');
            $statement = $db->prepare("INSERT INTO logs (username, attempt, time) VALUES (?, ?, ?)");
            $statement->execute([$username, $attempt, $time]);

            header('Location: /home');
            die;
        } else {
            // Log failed attempt
            $attempt = 'bad';
            $time = date('Y-m-d H:i:s');
            $statement = $db->prepare("INSERT INTO logs (username, attempt, time) VALUES (?, ?, ?)");
            $statement->execute([$username, $attempt, $time]);

            // Re-check the number of failed attempts after logging this attempt
            $statement = $db->prepare("SELECT COUNT(*) AS failed_attempts 
                                       FROM logs 
                                       WHERE username = :name AND attempt = 'bad' AND time > NOW() - INTERVAL 60 SECOND");
            $statement->bindValue(':name', $username);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result['failed_attempts'] >= 3) {
                // Update lockout status only if the user has 3 or more failed attempts
                $_SESSION["loginError"] = 'Account locked. Try again after 60 seconds.';
            } else {
                $_SESSION["loginError"] = 'Invalid username or password.';
            }

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
            $statement->closeCursor();
            $_SESSION["createError"] = 'Registration failed, please try again';
            header("Location: /create");
            die;
        }
    }
}
?>