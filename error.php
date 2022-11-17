<?php
session_start();

if (isset($_SESSION['login_user'])) {
    header('Location:index.php');
}

?>
<!doctype html>
<meta http-equiv="refresh" content="5;url=index" />
<html lang="en">
<?php
include('x-head.php')
?>

<body>
    <?php
    include('x-header.php');
    ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-6 mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body text-center">
                        <img src="assets/img/background/error.png" style="width:350px;">
                        <p style="margin-top: 0px;">Sorry, but the page you are looking for is not found.<br>Please, make sure you have typed the current URL.</p>
                        <a class="btn btn-warning" href="index">HOME PAGE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php
include('x-script.php');
?>
</html>