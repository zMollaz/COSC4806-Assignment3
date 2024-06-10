<?php
session_start();

class Log {

    public function __construct() {
        // Constructor code (if any)
    }
    
    public function test() {
        $db = db_connect();
        $statement = $db->prepare("SELECT * FROM logs;");
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);
        return $rows;
    }
    
    public function getFailedAttempts($username) {
        $db = db_connect();
        $statement = $db->prepare(
            "SELECT COUNT(*) AS failed_attempts 
                                   FROM logs 
                                   WHERE username = :name AND attempt = 'bad' AND time > NOW() - INTERVAL 60 SECOND");
        $statement->bindValue(':name', $username);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function logAttempt($username, $attempt) {
        $db = db_connect();
        $time = date('Y-m-d H:i:s');
        $statement = $db->prepare("INSERT INTO logs (username, attempt, time) VALUES (?, ?, ?)");
        $statement->execute([$username, $attempt, $time]);
    }
}
?>
