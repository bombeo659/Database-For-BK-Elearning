<?php
include('../session.php');
require_once("../class-user.php");
$auth_user = new USER();
$page_level = 1;
$auth_user->check_accesslevel($page_level);
$pageTitle = "Dashboard";
?>
<!doctype html>
<html lang="en">

<head>
    <?php
    include('x-meta.php');
    include('x-css.php');
    ?>
    <!-- Bootstrap core CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="../assets/css/dashboard.css" rel="stylesheet">
</head>

<body>
    <?php
    include('x-nav.php');
    ?>
    <div class="container-fluid">
        <div class="row">
            <?php
            include('x-sidenav.php');
            ?>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                </div>
                <div class="row">
                    <div class="col-3 col-sm-3">
                        <div class="card ">
                            <div class="card-header text-center" style="background-color: #f44336; padding: 42px 0;">
                            </div>
                            <div class="card-body text-center">
                                <div style="margin-top: -64px;">
                                    <img id="p_img" src="<?php $auth_user->getUserPic(); ?>" alt="Profile Image" runat="server" height="125" width="125" class="rounded-circle" style="border:1px solid; border-color: #f44336;">
                                </div>
                                <h6 class="mt-2"><?php $auth_user->profile_name(); ?></h6>
                                <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#change_picture">
                                    Change
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-9 col-sm-9">
                        <div class="card ">
                            <div class="card-header text-center" style=" border-bottom: 5px solid ;">
                                <strong>Profile Details</strong>
                                <div class="float-right">
                                    <i data-feather="info"></i>
                                </div>
                            </div>
                            <div class="card-body " style="min-height: 250px">
                                <table class="table table-striped">
                                    <tbody>
                                        <?php
                                        if ($auth_user->admin_level()) {
                                        ?>
                                            <tr>
                                                <th scope="row">Admin ID:</th>
                                                <td><?php $auth_user->profile_school_id() ?></td>
                                                <td colspan="1"><button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#change_id">Change</button></td>
                                            </tr>
                                        <?php
                                        } else if ($auth_user->instructor_level()) {
                                        ?>
                                            <tr>
                                                <th scope="row">Teacher ID:</th>
                                                <td><?php $auth_user->profile_school_id() ?></td>
                                                <td colspan="1"></td>
                                            </tr>
                                        <?php
                                        } else {
                                        ?>
                                            <tr>
                                                <th scope="row">Student ID:</th>
                                                <td><?php $auth_user->profile_school_id() ?></td>
                                                <td colspan="1"></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                        <tr>
                                            <th scope="row">Name:</th>
                                            <td><?php $auth_user->profile_name() ?></td>
                                            <td colspan="1">
                                            <?php
                                            if ($auth_user->admin_level()) {
                                            ?>
                                                <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#change_name">Change</button>
                                            <?php
                                            }
                                            ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Email:</th>
                                            <td id="profile_email"><?php $auth_user->profile_email() ?></td>
                                            <td colspan="1">
                                            <?php
                                            if ($auth_user->admin_level()) {
                                            ?>
                                                <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#change_email">Change</button>
                                            <?php
                                            }
                                            ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Address:</th>
                                            <td id="profile_address"><?php $auth_user->profile_address() ?></td>
                                            <td colspan="1"><button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#change_address">Change</button></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Password:</th>
                                            <td>********</td>
                                            <td colspan="1"><button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#change_password">Change</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Change Name -->
    <div class="modal fade" id="change_name" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <!-- <div class="btn-group"> -->
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>
    <!-- Change Address -->
    <div class="modal fade" id="change_address" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form id="change_address_form" enctype="multipart/form-data">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="update_address">Address<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="update_address" name="update_address" placeholder="" value="" required="">
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <!-- <div class="btn-group"> -->
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" id="btn_change_address" value="Save changes">
                    <!-- </div> -->
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Change Email -->
    <div class="modal fade" id="change_email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Email</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="change_email_form" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="update_email">Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="update_email" name="update_email" placeholder="" value="" required="">
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <!-- <div class="btn-group"> -->
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" id="btn_change_email" value="Save changes">
                    <!-- </div> -->
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Change Password -->
    <div class="modal fade" id="change_password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="change_password_form" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="update_password_old">Old Password<span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="update_password_old" name="update_password_old" placeholder="" value="" required="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="update_password_new">New Password<span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="update_password_new" name="update_password_new" placeholder="" value="" required="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="update_password_newconfirm">Confirm Password<span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="update_password_newconfirm" name="update_password_newconfirm" placeholder="" value="" required="">
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <!-- <div class="btn-group"> -->
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" id="btn_change_password" value="Save changes">
                    <!-- </div> -->
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Change Picture -->
    <div class="modal fade" id="change_picture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Picture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="change_picture_form" enctype="multipart/form-data">
                    <div class="modal-body text-center">
                        <img id="u_img" src="../assets/img/users/default.jpg" alt="Change Profile Image" runat="server" height="125" width="125" class="rounded-circle" style="border:1px solid; border-color: #ad1455;" />
                        <br><br>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="file" class="form-control" id="change_profile" name="change_profile">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <div class="btn-group"> -->
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" id="btn_change_picture" value="Save changes">
                        <!-- </div> -->
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    include('x-script.php');
    ?>

    <script type="text/javascript">
        feather.replace()
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#u_img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#change_profile").change(function() {
            readURL(this);
        });

        $(document).on('submit', '#change_picture_form', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            formData.append('action', "change_picture");
            var profileimg = '';
            if (document.getElementById("change_profile").files.length == 0) {
                console.log("no files selected");
                alertify.alert("No files selected").setHeader("Change Picture");
                return;
            } else {
                profileimg = $('#btn_change_picture').val();
            }
            $.ajax({
                url: "action/profile.php",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    var newdata = JSON.parse(data);
                    if (newdata.success) {
                        alertify.alert(newdata.success).setHeader('Change Picture');
                        $('#change_picture').modal('hide');
                        $('#c_img').attr('src', document.getElementById("u_img").src);
                        $('#p_img').attr('src', document.getElementById("u_img").src);
                    } else {
                        alertify.alert(newdata.error).setHeader('Change Picture');
                    }
                    $('#change_picture_form')[0].reset();
                }
            });
        });

        $(document).on('submit', '#change_password_form', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            formData.append('action', "change_password");
            $.ajax({
                url: "action/profile.php",
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var newdata = JSON.parse(data);
                    // alert(newdata.user_ID);
                    if (newdata.success) {
                        alertify.alert(newdata.success).setHeader('Change Password');
                        $('#change_password').modal('hide');
                    } else {
                        alertify.alert(newdata.error).setHeader('Change Password');
                    }
                    $('#change_password_form')[0].reset();
                }
            });
        });

        $(document).on('submit', '#change_email_form', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            formData.append('action', "change_email");
            $.ajax({
                url: "action/profile.php",
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var newdata = JSON.parse(data);
                    // alert(newdata.user_ID);
                    if (newdata.success) {
                        alertify.alert(newdata.success).setHeader('Change Email');
                        var update_email = $('#update_email').val();
                        $('#change_email').modal('hide');
                        $('#profile_email').html(update_email);
                    } else {
                        alertify.alert(newdata.error).setHeader('Change Email');
                    }
                    $('#change_email_form')[0].reset();
                }
            });
        });

        $(document).on('submit', '#change_address_form', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            formData.append('action', "change_address");
            $.ajax({
                url: "action/profile.php",
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var newdata = JSON.parse(data);
                    // alert(newdata.user_ID);
                    if (newdata.success) {
                        alertify.alert(newdata.success).setHeader('Change address');
                        var update_address = $('#update_address').val();
                        $('#change_address').modal('hide');
                        $('#profile_address').html(update_address);
                    } else {
                        alertify.alert(newdata.error).setHeader('Change address');
                    }
                    $('#change_address_form')[0].reset();
                }
            });
        });
    </script>
</body>

</html>