<?php
include('../session.php');
require_once("../class-user.php");
$auth_user = new USER();
$page_level = 1;
$auth_user->check_accesslevel($page_level);
$pageTitle = "Manage Classroom";
if (isset($_REQUEST["room_ID"])) {
    $this_room_ID = $_REQUEST["room_ID"];
}

?>
<!doctype html>
<html lang="en">

<head>
    <?php
    include('x-meta.php');
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
        .pagination>li>a {
            background-color: white;
            color: #5A4181;
        }

        .pagination>li>a:focus,
        .pagination>li>a:hover,
        .pagination>li>span:focus,
        .pagination>li>span:hover {
            color: #5a5a5a;
            background-color: #eee;
            border-color: #ddd;
        }

        .pagination>.active>a {
            color: white;
            background-color: darkslategrey !Important;
            border: solid 1px darkslategrey !Important;
        }

        .pagination>.active>a:hover {
            background-color: darkslategray !Important;
            border: solid 1px darkslategray;
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
            $rtab_n = "active_room";
            include('x-sidenav.php');
            ?>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Activity Board</h1>
                </div>

                <div class="table-responsive">
                    <?php
                    $room_ID = $_GET["room_ID"];
                    $rtab = "room_activity";
                    $rtab_c = "Activity";
                    include('x-roomtab.php');
                    ?>
                    <?php if ($auth_user->admin_level() || $auth_user->instructor_level()) { ?>
                        <button type="button" class="btn btn-sm btn-success add" data-toggle="modal" data-target="#test_modal">Add Activity</button>
                        <br><br>
                    <?php } ?>
                    <table class="table table-striped table-sm" id="activity_data">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Date Added</th>
                                <th>Date Expired</th>
                                <th>Timer</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <div class="modal fade" id="test_modal" tabindex="-1" role="dialog" aria-labelledby="product_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="test_modal_title">Add New Test</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="product_modal_content">

                                    <form method="post" id="test_form" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="test_name">Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="test_name" name="test_name" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="test_expired">Expired<span class="text-danger">*</span></label>
                                                <input type="datetime-local" class="form-control" id="test_expired" name="test_expired" value="" required="">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="test_timer">Timer(Min)<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="test_timer" name="test_timer" placeholder="00" value="" maxlength="4" minlength="1" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="prod_category">Type<span class="text-danger">*</span></label>
                                            <select class="form-control" id="test_type" name="test_type">
                                                <?php
                                                $auth_user->ref_test_type();
                                                ?>
                                            </select>
                                            <br>
                                            <label for="prod_category">Status<span class="text-danger">*</span></label>
                                            <select class="form-control" id="test_status" name="test_status">
                                                <?php
                                                $auth_user->ref_status();
                                                ?>
                                            </select>
                                        </div>
                                </div>
                                <div class="modal-footer">

                                    <input type="hidden" name="room_ID" id="room_ID" />
                                    <input type="hidden" name="test_ID" id="test_ID" />
                                    <input type="hidden" name="operation" id="operation" />
                                    <div class="btn-group" id="sbtng">
                                        <button type="button" class="btn btn-secondary mr-1 rounded" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary rounded submit" id="submit_input" value="test_submit">Submit</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="questionaire_modal" tabindex="-2" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Question and Answer</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body container">
                                    <button type="button" class="btn btn-sm btn-success add_question">Add Q&A</button>
                                    <table class="table table-borderless table-sm" id="questionaire_data">
                                        <thead>
                                            <tr>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal -->
                    <div class="modal fade" id="question_modal" tabindex="-3" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Question</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="post" id="question_form" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="q_question" class="col-sm-2 col-form-label">Question:</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="q_question" name="q_question">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="q_choice_a" class="col-sm-2 col-form-label">A.</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="q_choice_a" name="q_choice_a">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="q_choice_b" class="col-sm-2 col-form-label">B.</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="q_choice_b" name="q_choice_b">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="q_choice_c" class="col-sm-2 col-form-label">C.</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="q_choice_c" name="q_choice_c">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="q_choice_d" class="col-sm-2 col-form-label">D.</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="q_choice_d" name="q_choice_d">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="q_choice_b" class="col-sm-2 col-form-label">Correct</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="q_is_correct" name="q_is_correct">
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                    <option value="D">D</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="xtest_ID" id="xtest_ID" />
                                        <input type="hidden" name="question_ID" id="question_ID" />
                                        <input type="hidden" name="q_operation" id="q_operation" value="QandA_add" />
                                        <div class="btn-group" id="sbtng">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary submit" id="submit_input_q" value="question_submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="material_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Class Materials</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <button type="button" class="btn btn-sm btn-success add_materials">Add Materials</button>
                                    <br><br>
                                    <table class="table table-striped table-sm" id="material_data">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody> </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="material_submit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Materials</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="post" id="material_form" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="material_name" class="col-sm-2 col-form-label">Material Name:</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="material_name" name="material_name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="material_file" class="col-sm-2 col-form-label">File:</label>
                                            <div class="col-sm-10">
                                                <input type="file" class="form-control" id="material_file" name="material_file">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="room_ID" id="room_ID" value="<?php echo $this_room_ID ?>" />
                                        <input type="hidden" name="m_operation" id="m_operation" value="material_submit" />

                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary submit" id="submit_input_m" value="material_submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Modal -->
                    <div class="modal fade" id="taketest_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Test</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Display the countdown timer in an element -->
                                    <p id="test_countdown"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Submit Test</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="deltest_modal" tabindex="-1" role="dialog" aria-labelledby="product_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="test_modal_title">Delete this Activity</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-danger" id="test_delform">Delete</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- VIEW STUDENT SCORES -->
                    <div class="modal fade" id="student_scores" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Student Scores</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-striped table-sm" id="scorestudent_data">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Student ID</th>
                                                <th>Name</th>
                                                <th>Score</th>
                                            </tr>
                                        </thead>
                                        <tbody> </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>


            </main>
        </div>
    </div>

    <?php
    include('x-script.php');
    ?>

    <script type="text/javascript">
        $(document).ready(function() {

            var dataTable = $('#activity_data').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    url: "datatable/room_activity/fetch.php?room_ID=" + <?php echo $this_room_ID ?>,
                    type: "POST"
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],

            });
            
            <?php
            if ($auth_user->student_level()) {
            ?>
                dataTable.columns([6]).visible(false);
            <?php
            }
            ?>

            function qestionaire(test_ID) {
                var questionaire_dataTable = $('#questionaire_data').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "bAutoWidth": false,
                    "searching": false,
                    "ordering": false,
                    // "info":     false,
                    "order": [],
                    "ajax": {
                        url: "datatable/room_activity/fetch_questionaire.php?test_ID=" + test_ID,
                        type: "POST"
                    },
                    "columnDefs": [{
                        "targets": [0],
                        "orderable": false,
                    }, ],
                });

                $(document).on('submit', '#question_form', function(event) {
                    event.preventDefault();

                    $.ajax({
                        url: "datatable/room_test_qanda/insert.php",
                        method: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            alertify.alert(data).setHeader('Question and Answer');
                            $('#question_form')[0].reset();
                            $('#question_modal').modal('hide');

                            qestionaire(test_ID);
                            $('#questionaire_data').DataTable().destroy();
                            // questionaire_dataTable.ajax.reload();
                        }
                    });
                });
            }

            function scorestudent_test($test_ID) {
                var scorestud_dataTable = $('#scorestudent_data').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "order": [],
                    "ordering": false,
                    "bAutoWidth": false,
                    "searching": false,
                    "ajax": {
                        url: "datatable/room/fetch_studentscore.php?test_ID=" + $test_ID + "&room_ID=" + <?php echo $this_room_ID ?>,
                        type: "POST"
                    },
                    "columnDefs": [{
                        "targets": [0],
                        "orderable": false,
                    }, ],
                });
            }

            $(document).on('submit', '#test_form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: "datatable/room_activity/insert.php",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alertify.alert(data).setHeader('Test');
                        $('#test_form')[0].reset();
                        $('#test_modal').modal('hide');
                        dataTable.ajax.reload();
                    }
                });
            });

            $(document).on('click', '.add', function() {
                $('#test_modal_title').text('Add Activity');
                $("#test_name").prop("disabled", false);
                $('#test_form')[0].reset();

                var btng = document.getElementById("sbtng");
                btng.className = btng.className.replace(/\btng_null\b/g, "");
                btng.classList.add("btn-group");


                $('#submit_input').show();
                jQuery('#room_ID').val(<?php echo $this_room_ID ?>);

                $('#submit_input').text('Submit');
                $('#submit_input').val('test_submit');
                $('#operation').val("test_submit");
            });

            $(document).on('click', '.view', function() {
                var test_ID = $(this).attr("id");
                $('#test_modal_title').text('View Activity');
                $('#test_modal').modal('show');


                $('#submit_input').hide();
                var btng = document.getElementById("sbtng");
                btng.className = btng.className.replace(/\bbtn-group\b/g, "");
                btng.classList.add("btng_null");

                $.ajax({
                    url: "datatable/room_activity/fetch_single.php",
                    method: 'POST',
                    data: {
                        action: "test_view",
                        test_ID: test_ID
                    },
                    dataType: 'json',
                    success: function(data) {

                        $("#test_name").prop("disabled", true);
                        $("#test_expired").prop("disabled", true);
                        $("#test_timer").prop("disabled", true);
                        $("#test_type").prop("disabled", true);
                        $("#test_status").prop("disabled", true);

                        $('#test_name').val(data.test_Name);
                        $('#test_expired').val(data.test_Expired);
                        $('#test_timer').val(data.test_Timer);
                        $('#test_type').val(data.tstt_ID).change();
                        $('#test_status').val(data.status_ID).change();

                        $('#submit_input').hide();
                        $('#test_ID').val(test_ID);
                        $('#submit_input').text('Update');
                        $('#submit_input').val('test_view');
                        $('#operation').val("test_view");

                    }
                });


            });
            $(document).on('click', '.edit', function() {
                var test_ID = $(this).attr("id");
                $('#test_modal_title').text('Edit Activity');
                $('#test_modal').modal('show');

                $('#submit_input').show();
                var btng = document.getElementById("sbtng");
                btng.className = btng.className.replace(/\bbtng_null\b/g, "");
                btng.classList.add("btn-group");
                jQuery('#room_ID').val(<?php echo $this_room_ID ?>);

                $.ajax({
                    url: "datatable/room_activity/fetch_single.php",
                    method: 'POST',
                    data: {
                        action: "test_view",
                        test_ID: test_ID
                    },
                    dataType: 'json',
                    success: function(data) {
                        $("#test_name").prop("disabled", false);
                        $("#test_expired").prop("disabled", false);
                        $("#test_timer").prop("disabled", false);
                        $("#test_type").prop("disabled", false);
                        $("#test_status").prop("disabled", false);

                        $('#test_name').val(data.test_Name);
                        $('#test_expired').val(data.test_Expired);
                        $('#test_timer').val(data.test_Timer);
                        $('#test_type').val(data.tstt_ID).change();
                        $('#test_status').val(data.status_ID).change();

                        $('#submit_input').show();
                        $('#test_ID').val(test_ID);
                        $('#submit_input').text('Update');
                        $('#submit_input').val('test_edit');
                        $('#operation').val("test_edit");
                    }
                });


            });
            $(document).on('click', '.delete', function() {
                var test_ID = $(this).attr("id");
                // $('#deltest_modal').modal('show');
                // $('.submit').hide();

                // $('#test_ID').val(test_ID);
                alertify.confirm('Are you sure you want to delete this activity?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/room_activity/insert.php",
                            data: {
                                operation: "test_delete",
                                test_ID: test_ID
                            },
                            dataType: 'json',
                            complete: function(data) {
                                // $('#deltest_modal').modal('hide');
                                alertify.alert(data.responseText).setHeader('Delete Activity');
                                dataTable.ajax.reload();
                            }
                        })
                    },
                    function() {
                        alertify.error('Cancel')
                    }
                ).setHeader('Delete Activity');
            });

            $(document).on('click', '.view_questionaire', function() {
                var test_ID = $(this).attr("id");
                $('#questionaire_modal').modal('show');

                qestionaire(test_ID);

                $('#xtest_ID').val(test_ID);
                $('#questionaire_data').DataTable().destroy();


            });
            $(document).on('click', '.view_score', function() {
                var test_ID = $(this).attr("id");
                $('#student_scores').modal('show');
                console.log(test_ID);
                scorestudent_test(test_ID);
                $('#scorestudent_data').DataTable().destroy();
            });


            $(document).on('click', '.studview_score', function(event) {
                var test_ID = $(this).attr("id");
                $('#test_ID').val(test_ID);
                $.ajax({
                    url: "datatable/room_activity/insert.php",
                    method: 'POST',
                    data: {
                        operation: "test_view",
                        test_ID: test_ID
                    },
                    dataType: 'json',
                    complete: function(data) {
                        alertify.alert(data.responseText).setHeader('Test Score');
                    }
                });
            });

            $(document).on('click', '.add_question', function() {
                $('#question_modal').modal('show');
                $('#question_form')[0].reset();
                $('#submit_input_q').text('Submit');
                $('#submit_input_q').val('QandA_add');
                $('#q_operation').val("QandA_add");
            });

            $(document).on('click', '.edit_question', function() {
                var question_ID = $(this).attr("id");
                $('#question_modal').modal('show');
                $.ajax({
                    url: "datatable/room_test_qanda/fetch_single.php",
                    method: 'POST',
                    data: {
                        action: "QandA_edit",
                        question_ID: question_ID
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#question_ID').val(question_ID);
                        $('#q_question').val(data.q_question);
                        $('#q_choice_a').val(data.q_choice_a);
                        $('#q_choice_b').val(data.q_choice_b);
                        $('#q_choice_c').val(data.q_choice_c);
                        $('#q_choice_d').val(data.q_choice_d);
                        $('#q_is_correct').val(data.q_is_correct).change();
                        $('#submit_input_q').text('Update');
                        $('#submit_input_q').val('QandA_edit');
                        $('#q_operation').val("QandA_edit");
                    }
                });
            });

            $(document).on('click', '.take_test', function() {
                var test_ID = $(this).attr("id");
                $('#taketest_modal').modal('show');
                test_timer("Oct 1, 2019 15:37:25");
            });

            $(document).on('click', '#test_delform', function(event) {
                var test_ID = $('#test_ID').val();
                $.ajax({
                    type: 'POST',
                    url: "datatable/room_activity/insert.php",
                    data: {
                        operation: "test_delete",
                        test_ID: test_ID
                    },
                    dataType: 'json',
                    complete: function(data) {
                        $('#deltest_modal').modal('hide');
                        alertify.alert(data.responseText).setHeader('Delete this Account');
                        dataTable.ajax.reload();
                    }
                })
            });
        });
    </script>

    <script>
        // Set the date we're counting down to
        function test_timer(xdate) {
            var countDownDate = new Date(xdate).getTime();
            // Update the count down every 1 second
            var x = setInterval(function() {
                // Get todays date and time
                var now = new Date().getTime();
                // Find the distance between now an the count down date
                var distance = countDownDate - now;
                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                // Display the result in the element with id="demo"
                document.getElementById("test_countdown").innerHTML = days + "d " + hours + "h " +
                    minutes + "m " + seconds + "s ";
                // If the count down is finished, write some text
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("test_countdown").innerHTML = "EXPIRED";
                }
            }, 1000);
        }
    </script>
</body>

</html>