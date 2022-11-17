<?php
session_start();
require_once("class-user.php");
$auth_user = new USER();

//if user's logged in redirect to dashboard
if ($auth_user->is_loggedin() != "") {
    $auth_user->redirect_dashboard();
}
?>

<!doctype html>
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
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5 ">
                    <h5 class="card-title text-center" id="f_text" style="background-color: #eb0506; padding: 15px; color: white; border-radius: 5px 5px 0px 0px;">Sign In</h5>
                    <div class="card-body">
                        <hr style="margin-top: -30px;">
                        <div class="text-center msg">
                            <img src="assets/img/logo/logo-bk.png" alt="BKLogo" style="width: 100px;">
                            <h5>Ho Chi Minh City University Of Technology</h5>
                            <h3>Bach Khoa E-Learning</h3>
                            <small id="f_stext">Login here using your username and password</small>
                        </div>
                        <div id="f_login">
                            <form class="form-signin" id="login_form" method="POST">
                                <div class="form-label-group">
                                    <input type="text" id="inputUsername" class="form-control" placeholder="Username" name="login_user" required autofocus>
                                    <label for="inputUsername">Username</label>
                                </div>
                                <div class="form-label-group">
                                    <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="login_password" required>
                                    <label for="inputPassword">Password</label>
                                </div>
                                <input type="hidden" name="operation" value="submit_login">
                                <button class="btn btn-lg btn-primary btn-block" type="submit" style="background-color: #e91e63;border: none !important;" name="submit_login">Sign in</button>
                                <!-- <div class="text-center">
                                    Don't have an account? <a href="#" id="a_sign" >Sign up</a>
                                </div> -->
                            </form>
                        </div>
                        <div id="f_register">
                            <form class="form-signin" id="register_form" method="POST">
                                <div class="form-label-group">
                                    <input type="password" id="reg_studentnum" class="form-control" placeholder="Student Number" name="reg_studentnum" required>
                                    <label for="acc_username">LRN Number</label>
                                </div>
                                <div class="form-row">
                                    <div class="form-label-group col-md-6">
                                        <input type="password" id="reg_password" class="form-control" placeholder="Password" name="reg_password" required>
                                        <label for="acc_password">Password</label>
                                    </div>
                                    <div class="form-label-group col-md-6">
                                        <input type="password" id="reg_cpassword" class="form-control" placeholder="Confirm Password" name="reg_cpassword" required>
                                        <label for="acc_cpassword">Confirm Password</label>
                                    </div>
                                </div>
                                <div class="form-label-group">
                                    <input type="email" id="reg_email" class="form-control" placeholder="Email" name="reg_email" required>
                                    <label for="acc_email">Email</label>
                                </div>

                                <input type="hidden" name="operation" value="submit_register">
                                <button class="btn btn-lg btn-primary btn-block" type="submit" style="background-color: #e91e63;border: none !important;" name="submit_register">Register</button>
                                <div class="text-center">
                                    Already have an account? <a href="#" id="a_login">Login</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php
include('x-script.php');
?>
<script type="text/javascript">
    // hide register form
    $('#f_register').hide();

    $(document).on('submit', '#login_form', function(event) {
        event.preventDefault();
        $.ajax({
            url: "data-action.php",
            method: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            type: 'html',
            success: function(data) {
                var newdata = JSON.parse(data);
                if (newdata.success) {
                    alertify.alert(newdata.success).setHeader('Login Success');
                    window.location.assign("dashboard/");
                } else {
                    alertify.alert(newdata.error).setHeader('Error Login');
                }
                $("#login_form")[0].reset();
            }
        });

    });
    $(document).on('submit', '#register_form', function(event) {
        event.preventDefault();
        $.ajax({
            url: "data-action.php",
            method: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            type: 'html',
            success: function(data) {
                var newdata = JSON.parse(data);
                if (newdata.success) {
                    alertify.alert(newdata.success).setHeader('Register Success');
                    $('#f_register').hide();
                    $('#f_login').show();
                } else {
                    alertify.alert(newdata.error).setHeader('Error Register');
                }
            }
        });

    });
    $(document).on('click', '#a_sign', function() {

        $('#f_text').text('Register');
        $('#f_stext').text('Fill-up to register');

        $('#f_login').hide();
        $('#f_register').show();
    });
    $(document).on('click', '#a_login', function() {
        $('#f_text').text('Login');
        $('#f_stext').text('Login here using your username and password');
        $('#f_login').show();
        $('#f_register').hide();
    });
</script>

</html>