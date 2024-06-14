  <?php 
  require_once 'app/views/templates/headerPublic.php';
  session_start();
  $error = '';
  if (isset($_SESSION["createError"]) && $_SESSION["createError"] == true) {
      $error = $_SESSION["createError"];
  }
  ?>
  <main role="main" class="container">
      <div class="page-header d-flex justify-content-between align-items-center" id="banner">
          <div>
              <h1>Create account</h1>
          </div>
          <div>
          </div>
      </div>

      <div class="row">
          <div class="col-sm-auto">
              <?php if ($error): ?>
                  <div class="alert alert-danger" role="alert">
                      <?php echo $error; ?>
                  </div>
              <?php endif; ?>
              <form action="/create/newUser" method="post">
                  <fieldset>
                      <div class="form-group">
                          <label for="username">Username:</label>
                          <br>
                          <input type="text" id="username" name="username" placeholder="Enter your username" class="form-control" required>
                      </div>
                      <div class="form-group">
                          <label for="password">Password:</label>
                          <br>
                          <input type="password" id="password" name="password" placeholder="Enter your password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}" title="Password must contain at least 8 characters, including one uppercase letter, one lowercase letter, one number, and one special character." class="form-control" required>
                      </div>
                      <div class="form-group">
                          <label for="password">Verify password:</label>
                          <br>
                          <input type="password" id="verifypassword" name="verifypassword" class="form-control" placeholder="Re-enter your password" required>
                      </div>
                      <br>
                      <button type="submit" class="btn btn-primary">Submit</button>
                  </fieldset>
              </form> 
              <br>
              <br>
              <a href="index.php">Home page</a>
          </div>
      </div>
  </main>
  <?php require_once 'app/views/templates/footer.php' ?>
