<?php
include('../session.php');
require_once("../class-user.php");

$auth_user = new USER();
$page_level = 2;
$auth_user->check_accesslevel($page_level);
$pageTitle = "Manage Question And Answer";

if (isset($_REQUEST["test_ID"])) {
    $test_ID = $_REQUEST["test_ID"];
    $test_type = $_REQUEST["type"];

    $result = $auth_user->get_test($test_ID);

    foreach ($result as $row) {
        $test_Name = $row["test_name"];
        $test_Timer = $row["test_timer"];
    }
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
            include('x-sidenav.php');
            ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?php echo $test_Name ?></h1>

                </div>

                <div class="table-responsive">
                    <button type="button" class="btn btn-sm btn-success add_question">
                        Add Q/A
                    </button>

                    <br><br>
                    <table class="table table-borderless table-sm" id="questionaire_data">
                        <thead>
                            <tr>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <?php
                    if (isset($test_type)) {
                        if ($test_type == 2) {
                    ?>
                            <button type="button" class="btn btn-sm btn-success add_tf">
                                Add True/False
                            </button>

                            <br><br>
                            <table class="table table-borderless table-sm" id="tf_data">
                                <thead>
                                    <tr>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                    <?php
                        }
                    }
                    ?>
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
                                                <input type="text" class="form-control" id="q_question" name="q_question" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="q_choice_a" class="col-sm-2 col-form-label">A.</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="q_choice_a" name="q_choice_a" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="q_choice_b" class="col-sm-2 col-form-label">B.</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="q_choice_b" name="q_choice_b" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="q_choice_c" class="col-sm-2 col-form-label">C.</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="q_choice_c" name="q_choice_c" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="q_choice_d" class="col-sm-2 col-form-label">D.</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="q_choice_d" name="q_choice_d" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="q_choice_b" class="col-sm-2 col-form-label">Correct</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="q_is_correct" name="q_is_correct" required>
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
                                        <input type="hidden" name="xtype" id="xtype" value="1" />
                                        <input type="hidden" name="q_operation" id="q_operation" value="QandA_add" />
                                        <div class="" id="sbtng">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary submit" id="submit_input_q" value="question_submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="tf_modal" tabindex="-3" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add True/Flase</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="post" id="tf_form" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="tf_question" class="col-sm-2 col-form-label">Question:</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="tf_question" name="tf_question" required>
                                            </div>
                                        </div>
                                        <!--  <div class="form-group row">
              <label for="tf_choice_true" class="col-sm-2 col-form-label">A.</label>
              <div class="col-sm-10">
                <input type="hidden" class="form-control" id="tf_choice_true" name="tf_choice_true" value="TRUE" required>
              </div>
            </div>

             <div class="form-group row">
              <label for="tf_choice_false" class="col-sm-2 col-form-label">B.</label>
              <div class="col-sm-10">
                <input type="hidden" class="form-control" id="tf_choice_false" name="tf_choice_false" value="FALSE" required>
              </div>
            </div> -->

                                        <input type="hidden" class="form-control" id="tf_choice_true" name="tf_choice_true" value="TRUE" required>
                                        <input type="hidden" class="form-control" id="tf_choice_false" name="tf_choice_false" value="FALSE" required>

                                        <!-- <input type="hidden" id="tf_is_correct" name="tf_is_correct" value="A"> -->
                                        <div class="form-group row">
                                            <label for="tf_is_correct" class="col-sm-2 col-form-label">Correct</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="tf_is_correct" name="tf_is_correct" required>
                                                    <option value="A">TRUE</option>
                                                    <option value="B">FALSE</option>
                                                </select>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="xtest_ID" id="xxtest_ID" />
                                        <input type="hidden" name="tf_ID" id="tf_ID" />
                                        <input type="hidden" name="xtype" id="xtype" value="2" />
                                        <input type="hidden" name="tf_operation" id="tf_operation" value="TF_add" />
                                        <div class="" id="sbtng">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary submit" id="submit_input_tf" value="tf_submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
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
            $('#xtest_ID').val(<?php echo $test_ID ?>);
            $('#xxtest_ID').val(<?php echo $test_ID ?>);
            // $('#questionaire_data').DataTable().destroy();

            var questionaire_dataTable = $('#questionaire_data').DataTable({
                "processing": true,
                "serverSide": true,
                "bAutoWidth": false,
                "searching": false,
                "ordering": false,
                // "info":     false,
                "order": [],
                "ajax": {
                    url: "datatable/room_activity/fetch_questionaire.php?test_ID=" + <?php echo $test_ID ?> + "&type=1",
                    type: "POST"

                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],

            });
            var tf_dataTable = $('#tf_data').DataTable({
                "processing": true,
                "serverSide": true,
                "bAutoWidth": false,
                "searching": false,
                  "ordering": false,
                // "info":     false,
                "order": [],
                "ajax": {
                    url: "datatable/room_activity/fetch_questionaire.php?test_ID=" + <?php echo $test_ID ?> + "&type=2",
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

                        questionaire_dataTable.ajax.reload();

                        // questionaire_dataTable.ajax.reload();
                    }
                });

            });
            $(document).on('submit', '#tf_form', function(event) {
                event.preventDefault();

                $.ajax({
                    url: "datatable/room_test_qanda/insert.php",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alertify.alert(data).setHeader('Question and Answer');
                        $('#tf_form')[0].reset();
                        $('#tf_modal').modal('hide');

                        tf_dataTable.ajax.reload();

                        // questionaire_dataTable.ajax.reload();
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
                    url: "datatable/room_test_qanda/fetch_single.php?type=1",
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

            $(document).on('click', '.delete_question', function() {
                var question_ID = $(this).attr("id");
                alertify.confirm('Are you sure you want to delete this question?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/room_test_qanda/insert.php",
                            data: {
                                q_operation: "QandA_delete",
                                question_ID: question_ID
                            },
                            dataType: 'json',
                            complete: function(data) {
                                alertify.alert(data.responseText).setHeader('Question and Answer');
                                questionaire_dataTable.ajax.reload();

                            }
                        })
                    },
                    function() {
                        alertify.error('Cancel')
                    }).setHeader('Question and Answer');

            });

            $(document).on('click', '.add_tf', function() {


                $('#tf_modal').modal('show');
                $('#tf_form')[0].reset();
                $('#submit_input_tf').text('Submit');
                $('#submit_input_tf').val('TF_add');
                $('#tf_operation').val("TF_add");


            });
            $(document).on('click', '.edit_tf', function() {
                var question_ID = $(this).attr("id");
                $('#tf_modal').modal('show');


                $.ajax({
                    url: "datatable/room_test_qanda/fetch_single.php?type=2",
                    method: 'POST',
                    data: {
                        action: "TF_edit",
                        question_ID: question_ID
                    },
                    dataType: 'json',
                    success: function(data) {

                        $('#tf_ID').val(question_ID);
                        $('#tf_question').val(data.q_question);
                        $('#tf_choice_true').val(data.q_choice_a);
                        $('#tf_choice_false').val(data.q_choice_b);
                        $('#tf_is_correct').val(data.q_is_correct).change();

                        $('#submit_input_tf').text('Update');
                        $('#submit_input_tf').val('TF_edit');
                        $('#tf_operation').val("TF_edit");

                    }
                });


            });
            $(document).on('click', '.delete_tf', function() {
                var tf_ID = $(this).attr("id");
                alertify.confirm('Are you sure you want to delete this true/false?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/room_test_qanda/insert.php",
                            data: {
                                tf_operation: "TF_delete",
                                tf_ID: tf_ID
                            },
                            dataType: 'json',
                            complete: function(data) {
                                alertify.alert(data.responseText).setHeader('True / False');
                                tf_dataTable.ajax.reload();

                            }
                        })
                    },
                    function() {
                        alertify.error('Cancel')
                    }).setHeader('Question and Answer');

            });

        });
    </script>
</body>

</html>