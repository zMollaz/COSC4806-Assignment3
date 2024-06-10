<?php

class Create extends Controller {

    public function index() {		
	    $this->view('create/index'); 
    }
  
    public function newUser() {		
      $username = strtolower(trim($_POST['username']));
      $password = trim($_POST['password']);
      $verifypassword = trim($_POST['verifypassword']);
      $user = $this->model('User');
      $user->create($username, $password, $verifypassword);   
    }
}

?>