<?php
include('../session.php');


require_once("../class-user.php");


$auth_user = new USER();
// $page_level = 3;
// $auth_user->check_accesslevel($page_level);
$pageTitle = "Manage Question And Answer";

if (isset($_REQUEST["test_ID"])) {
    $test_ID = $_REQUEST["test_ID"];
    $room_ID = $_REQUEST["room_ID"];


    $result = $auth_user->get_test($test_ID);

    foreach ($result as $row) {
        $test_Name = $row["test_name"];
        $test_Timer = $row["test_timer"];
    }
}
$stmt4 = $auth_user->runQuery("SELECT * FROM `test_attemp` WHERE user_id = " . $_SESSION['user_id'] . " AND test_id = " . $test_ID . "");
$stmt4->execute();
$result4 = $stmt4->fetchAll();


$retake_count = 0;
if ($stmt4->rowCount() > 0) {
    foreach ($result4 as $row) {
        // $atmp_ID = $row["atmp_ID"];
        $retake_count = $row["count"];
    }
} else {
    $stmt5 = $auth_user->runQuery("INSERT INTO `test_attemp` (`user_id`, `test_id`, `count`) 
      VALUES (" . $_SESSION['user_id'] . ", " . $test_ID . ", '3');");
    $stmt5->execute();
    $stmt6 = $auth_user->runQuery("SELECT * FROM `test_attemp` WHERE user_id = " . $_SESSION['user_id'] . " AND test_id = " . $test_ID . "");
    $stmt6->execute();
    $result5 = $stmt6->fetchAll();
    foreach ($result5 as $row) {
        // $atmp_ID = $row["atmp_ID"];
        $retake_count = $row["count"];
    }
}



if ($retake_count <= 0) {

    echo "<script>alert('You Exceed Retake Count');window.close()</script>";
}

$stmtz9 = $auth_user->runQuery("SELECT * FROM `test_question` WHERE test_id =  " . $test_ID . "");
$stmtz9->execute();
if ($stmtz9->rowCount() == 0) {
    echo "<script>alert('No Question Available');window.close()</script>";
}


?>
<!doctype html>
<html lang="en">

<head>
    <?php
    include('x-meta.php');
    ?>


    <?php
    include('x-css.php');
    ?>




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
                    <h1 class="h2"><?php echo $test_Name ?></h1>
                    <div class="btn btn-info btn-sm float-right " id="retake">RETAKE(<?php echo $retake_count ?>)</div>

                </div>

                <div class="table-responsive">

                    <div class="card-body " style="min-height: 250px">

                        <div id="test_countdown" class="btn btn-primary float-right">00:00</div>
                        <br>
                        <br>
                        <form method="post" id="test_form" enctype="multipart/form-data">
                            <?php

                            $auth_user->test_question($test_ID);


                            ?>
                            <input type="hidden" name="q_operation" value="QandA_answer">
                            <input type="hidden" name="q_testID" value="<?php echo $test_ID ?>">
                            <button type="submit" class="btn btn-primary submit" id="submit_input" value="QandA_answer">Submit</button>
                        </form>
                    </div>



                </div>
            </main>
        </div>
    </div>

    <?php
    include('x-script.php');
    ?>
    <script>
        $(document).ready(function() {
            $(document).on('submit', '#test_form', function(event) {
                event.preventDefault();

                $.ajax({
                    url: "datatable/room_test_qanda/insert.php",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {

                        alertify.alert(data.msg,
                            function() {
                                window.location.assign('result?test_ID=' + data.test_ID + '&room_ID=' + <?php echo $room_ID ?>);
                                alertify.success('Ok')
                            }).setHeader('Answer');

                    }
                });

            });
        });


        test_timer(<?php echo $auth_user->test_time($test_ID) ?>, <?php echo $test_ID ?>);


        function test_timer(test_time, roomID) {

            var xmin = new Date();
            xmin.setMinutes(xmin.getMinutes() + test_time);

            var countDownDate = xmin.getTime();

            // Update the count down every 1 second
            var x = setInterval(function() {

                // Get todays date and time
                var now = new Date().getTime();

                // Find the distance between now an the count down date
                var distance = countDownDate - now;

                // $("#sampletime").html(distance);
                $.ajax({
                    type: 'POST',
                    url: "datatable/room_activity/timmer.php",
                    data: {
                        timmerutc: distance,
                        test_ID: <?php echo $test_ID ?>
                    },
                    dataType: 'json',
                    success: function(data) {
                        var distance = data.remaining;
                        // Time calculations for days, hours, minutes and seconds
                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        // Display the result in the element with id="demo"
                        document.getElementById("test_countdown").innerHTML = hours + "h " +
                            minutes + "m " + seconds + "s ";
                        // If the count down is finished, write some text
                        console.log(distance);



                        if (distance < 0) {
                            clearInterval(x);
                            alertify.alert("Sorry you exceed the timelimit. Your answer will send automatically").setHeader('Time Out');
                            $('#test_form').submit();
                            document.getElementById("test_countdown").innerHTML = "EXPIRED";
                        }
                    }

                });


            }, 1000);
        }
    </script>
</body>

</html>