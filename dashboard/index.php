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
    ?>
    <!-- Bootstrap core CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="../assets/css/icomoon/styles.css" rel="stylesheet" type="text/css">

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
        ul#myTab.nav.nav-tabs a {
            color: black !important;
        }

        ul#myTab.nav.nav-tabs .nav-link:hover {
            color: white !important;
        }

        ul#myTab.nav.nav-tabs .nav-link.active:hover {

            color: black !important;
        }

        ul#myTab.nav.nav-tabs .nav-link.active {
            background-color: #e9ecef !important;
            /*color:white!important;*/
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
                <div class="row d-flex" style="align-items: center;">
                    <div class="col-sm-3 d-flex justify-content-end" style="min-height: 100px; align-items: center;">
                        <img src="../assets/img/logo/logo-min.png" height="80" > 
                    </div>
                    <div class="col-sm-9 d-flex justify-content-start">
                        <h3>HO CHI MINH CITY UNIVERSITY OF TECHNOLOGY</h3>
                    </div>
                </div>
                <div class="row mt-3">
                    <!-- ADMIN -->
                    <?php if ($auth_user->admin_level()) { ?>
                        <div class="col-6 col-sm-6">
                            <div class="card ">
                                <div class="card-header text-center" style="border-bottom: 5px solid;">
                                    <strong>MISSION</strong>
                                </div>
                                <div class="card-body text-center" style="min-height: 250px">
                                    <h5>Training human resources of international quality.</h5> <br>
                                    <h5>Create new knowledge through scientific research - technology transfer, start-up - innovation.</h5> <br>
                                    <h5>Performing social responsibility and serving the community.</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-6">
                            <div class="card ">
                                <div class="card-header text-center" style="border-bottom: 5px solid;">
                                    <strong>VISION</strong>
                                </div>
                                <div class="card-body text-center" style="min-height: 250px">
                                    <h5>Globally recognized as the leading university in the region for teaching, learning, research and entrepreneurship - innovation.</h5>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- STUDENT -->
                    <?php if ($auth_user->student_level()) { ?>
                        <div class="col-12 col-sm-12" style="padding-bottom:5px;">
                            <div class="card ">
                                <div class="card-header bg-dark text-white" style=" border-bottom: 5px solid #adb5bd;">
                                    <strong>Basic Information</strong>
                                </div>
                                <div class="card-body d-flex row" style="align-items: center; min-height: 200px">
                                    <div class="col-lg-4 d-flex justify-content-center">
                                        <img src="<?php $auth_user->getUserPic(); ?>" height="125" width="125" class="rounded-circle" style="border:1px solid; border-color: #4caf50;">
                                    </div>

                                    <div class="col-lg-8">
                                        <h3><b>Name:</b> <?php $auth_user->profile_name() ?> </h3>
                                        <h3><b>Student ID:</b> <?php $auth_user->profile_school_id() ?></h3>
                                        <h3><b>Gender:</b> <?php $auth_user->profile_sex() ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- INSTRUCTOR -->
                    <?php if ($auth_user->instructor_level()) { ?>
                        <div class="col-12 col-sm-12" style="padding-bottom:5px;">
                            <div class="card ">
                                <div class="card-header bg-dark text-white" style=" border-bottom: 5px solid #adb5bd ;">
                                    <strong>Basic Information</strong>
                                </div>
                                <div class="card-body d-flex row" style="align-items: center; min-height: 200px">
                                    <div class="col-lg-4 d-flex justify-content-center">
                                        <img src="<?php $auth_user->getUserPic(); ?>" height="125" width="125" class="rounded-circle" style="border:1px solid; border-color: #4caf50;">
                                    </div>

                                    <div class="col-lg-8">
                                        <h3><b>Name:</b> <?php $auth_user->profile_name() ?> </h3>
                                        <h3><b>Teacher ID:</b> <?php $auth_user->profile_school_id() ?></h3>
                                        <h3><b>Gender:</b> <?php $auth_user->profile_sex() ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </main>
        </div>
    </div>
    <?php
    include('x-script.php');
    ?>
</body>
</html>