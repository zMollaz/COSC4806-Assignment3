<?php require_once 'app/views/templates/header.php' ?>
<div class="container">
    <div class="row">
        <div class="col-lg-12 mt-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="card-title">Welcome <?php echo $_SESSION["username"]; ?></h1>
                        <p class="card-text">You are now logged in.</p>
                    </div>
                    <div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="/logout" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'app/views/templates/footer.php' ?>
