<?php 
require_once 'app/views/templates/headerPublic.php';
	
session_start();
$error = '';
	if (isset($_SESSION["loginError"]) && $_SESSION["loginError"] == true) {
		$error = $_SESSION["loginError"];
	}
?>
<main role="main" class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-12">
							<h1>Login</h1>
							<?php if ($error): ?>
								<p style="color:red;"><?php echo $error; ?></p>
							<?php endif; ?>
            </div>
        </div>
    </div>

<div class="row">
    <div class="col-sm-auto">
		<form action="/login/verify" method="post" >
		<fieldset>
			<div class="form-group">
				<label for="username">Username</label>
				<input required type="text" class="form-control" name="username">
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input required type="password" class="form-control" name="password">
			</div>
            <br>
		    <button type="submit" class="btn btn-primary">Login</button>
		</fieldset>
		</form> 
		<br>
		<br>
		<a href="/create">Create account</a>
	</div>
</div>
    <?php require_once 'app/views/templates/footer.php' ?>
