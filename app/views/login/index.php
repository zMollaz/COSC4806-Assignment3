<?php 
require_once 'app/views/templates/headerPublic.php';

session_start();
$error = '';
if (isset($_SESSION["loginError"]) && $_SESSION["loginError"] == true) {
		$error = $_SESSION["loginError"];
}
?>
<main role="main" class="container">
		<div class="page-header d-flex justify-content-between align-items-center" id="banner">
				<div>
						<h1>Login</h1>
				</div>
				<div>
						<p class="text-end mb-0">Today is <?php echo date("l jS \of F Y"); ?></p>
				</div>
		</div>

		<div class="row">
				<div class="col-sm-auto">
						<?php if ($error): ?>
								<div class="alert alert-danger" role="alert">
										<?php echo $error; ?>
								</div>
						<?php endif; ?>
						<form action="/login/verify" method="post">
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
</main>
<?php require_once 'app/views/templates/footer.php' ?>
