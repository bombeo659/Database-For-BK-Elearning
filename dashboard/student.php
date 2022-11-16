<?php
include('../session.php');
require_once("../class-user.php");

$auth_user = new USER();
$page_level = 3;
$auth_user->check_accesslevel($page_level);
$pageTitle = "Manage Student";
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
            border: solid 1px darkslategray !Important;
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
                    <h1 class="h2">Manage Student</h1>

                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bcrum bg-dark">
                        <li class="breadcrumb-item "><a href="index" class="bcrum_i_a">Dashboard</a></li>
                        <li class="breadcrumb-item  active bcrum_i_ac" aria-current="page">Student Management</li>
                    </ol>
                </nav>
                <div class="table-responsive">
                    <button type="button" class="btn btn-sm btn-success add">
                        Add Student
                    </button>
                    <br><br>
                    <table class="table table-striped table-sm" id="student_data">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Sex</th>
                                <th>Marital</th>
                                <th>Account Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>


                    <!--modal student -->
                    <div class="modal fade" id="student_modal" tabindex="-1" role="dialog" aria-labelledby="student_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="student_modal_title">Add Attendance</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="post" id="student_form" enctype="multipart/form-data">
                                    <div class="modal-body" id="product_modal_content">
                                        <div class="form-row">

                                            <div class="form-group col-md-4">
                                                <img id="s_img" src="../assets/img/users/default.jpg" alt="Student Image" runat="server" height="125" width="125" class="img-thumbnail" style="border:1px solid; border-color: #4caf50; min-width:125px; min-height:125px; max-width:125px; max-height:125px; background-size:cover;" />
                                                <br><br>
                                                <input type="file" class="form-control" id="student_img" name="student_img" placeholder="" value="">
                                            </div>
                                            <div class="form-group col-md-4">

                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="student_lrn">Student ID<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="student_lrn" name="student_lrn" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="student_fname">First Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="student_fname" name="student_fname" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="student_mname">Middle Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="student_mname" name="student_mname" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="student_lname">Last Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="student_lname" name="student_lname" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="student_suffix">Suffix<span class="text-danger">*</span></label>
                                                <select class="form-control" id="student_suffix" name="student_suffix">
                                                    <?php
                                                    $auth_user->user_suffix_option();
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="student_bday">Birthday<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="student_bday" name="student_bday" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="student_sex">Sex<span class="text-danger">*</span></label>
                                                <select class="form-control" id="student_sex" name="student_sex" required="">
                                                    <?php
                                                    $auth_user->user_sex_option();
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="student_marital">Marital<span class="text-danger">*</span></label>
                                                <select class="form-control" id="student_marital" name="student_marital" required="">
                                                    <?php
                                                    $auth_user->user_marital_option();
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="student_email">Email<span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="student_email" name="student_email" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="student_address">Address<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="student_address" name="student_address" placeholder="" value="" required="">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="student_ID" id="student_ID" />
                                        <input type="hidden" name="operation" id="operation" />
                                        <div class="btn-group" id='sbtng'>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary submit" id="submit_input" value="submit_student">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--/modal student -->

                    <!--delete modal -->
                    <div class="modal fade" id="delstudent_modal" tabindex="-1" role="dialog" aria-labelledby="student_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="student_modal_title">Delete this Student</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-danger" id="student_delform">Delete</button>
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
                    <!--/delete modal -->


                </div>
            </main>
        </div>
    </div>

    <?php
    include('x-script.php');
    ?>

    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#s_img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#student_img").change(function() {
            readURL(this);
        });

        $(document).ready(function() {

            var student_dataTable = $('#student_data').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    url: "datatable/student/fetch.php",
                    type: "POST"
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],

            });
            student_dataTable.columns([1]).visible(false);


            $(document).on('submit', '#student_form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: "datatable/student/insert.php",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alertify.alert(data).setHeader('Student Record');
                        $('#student_form')[0].reset();
                        $('#student_modal').modal('hide');
                        student_dataTable.ajax.reload();
                    }
                });

            });

            $(document).on('click', '.add', function() {
                $('#student_modal_title').text('Add New Student');
                $('#student_modal').modal('show');
                $('#student_form')[0].reset();

                var btng = document.getElementById("sbtng");
                btng.className = btng.className.replace(/\btng_null\b/g, "");
                btng.classList.add("btn-group");

                $('#s_img').attr('src', "../assets/img/users/default.jpg");
                $("#student_lrn").prop("disabled", false);
                $("#student_fname").prop("disabled", false);
                $("#student_mname").prop("disabled", false);
                $("#student_lname").prop("disabled", false);
                $("#student_suffix").prop("disabled", false);
                $("#student_bday").prop("disabled", false);
                $("#student_sex").prop("disabled", false);
                $("#student_marital").prop("disabled", false);
                $("#student_email").prop("disabled", false);
                $("#student_address").prop("disabled", false);


                $("#student_img").show();
                $('#submit_input').show();

                $('#submit_input').text('Submit');
                $('#submit_input').val('submit_student');
                $('#operation').val("submit_student");
            });

            $(document).on('click', '.view', function() {
                var student_ID = $(this).attr("id");
                $('#student_modal_title').text('View Student');
                $('#student_modal').modal('show');


                $('#submit_input').hide();
                var btng = document.getElementById("sbtng");
                btng.className = btng.className.replace(/\bbtn-group\b/g, "");
                btng.classList.add("btng_null");


                $("#student_img").hide();

                $.ajax({
                    url: "datatable/student/fetch_single.php",
                    method: 'POST',
                    data: {
                        action: "student_view",
                        student_ID: student_ID
                    },
                    dataType: 'json',
                    success: function(data) {
                        $("#student_lrn").prop("disabled", true);
                        $("#student_fname").prop("disabled", true);
                        $("#student_mname").prop("disabled", true);
                        $("#student_lname").prop("disabled", true);
                        $("#student_suffix").prop("disabled", true);
                        $("#student_bday").prop("disabled", true);
                        $("#student_sex").prop("disabled", true);
                        $("#student_marital").prop("disabled", true);
                        $("#student_email").prop("disabled", true);
                        $("#student_address").prop("disabled", true);


                        $('#s_img').attr('src', data.student_img);
                        $('#student_lrn').val(data.student_lrn);
                        $('#student_fname').val(data.student_fname);
                        $('#student_mname').val(data.student_mname);
                        $('#student_lname').val(data.student_lname);
                        $('#student_suffix').val(data.student_suffix).change();
                        $('#student_bday').val(data.student_bday);
                        $('#student_sex').val(data.student_sex).change();
                        $('#student_marital').val(data.student_marital).change();
                        $('#student_email').val(data.student_email);
                        $('#student_address').val(data.student_address);

                        $('#submit_input').hide();
                        $('#student_ID').val(student_ID);
                        $('#submit_input').text('Update');
                        $('#submit_input').val('student_view');
                        $('#operation').val("student_view");
                    }
                });
            });


            $(document).on('click', '.edit', function() {
                var student_ID = $(this).attr("id");
                $('#student_modal_title').text('View Student');
                $('#student_modal').modal('show');


                var btng = document.getElementById("sbtng");
                btng.className = btng.className.replace(/\btng_null\b/g, "");
                btng.classList.add("btn-group");


                $("#student_img").show();

                $.ajax({
                    url: "datatable/student/fetch_single.php",
                    method: 'POST',
                    data: {
                        action: "student_update",
                        student_ID: student_ID
                    },
                    dataType: 'json',
                    success: function(data) {
                        $("#student_lrn").prop("disabled", false);
                        $("#student_fname").prop("disabled", false);
                        $("#student_mname").prop("disabled", false);
                        $("#student_lname").prop("disabled", false);
                        $("#student_suffix").prop("disabled", false);
                        $("#student_bday").prop("disabled", false);
                        $("#student_sex").prop("disabled", false);
                        $("#student_marital").prop("disabled", false);
                        $("#student_email").prop("disabled", false);
                        $("#student_address").prop("disabled", false);


                        $('#s_img').attr('src', data.student_img);
                        $('#student_lrn').val(data.student_lrn);
                        $('#student_fname').val(data.student_fname);
                        $('#student_mname').val(data.student_mname);
                        $('#student_lname').val(data.student_lname);
                        $('#student_suffix').val(data.student_suffix).change();
                        $('#student_bday').val(data.student_bday);
                        $('#student_sex').val(data.student_sex).change();
                        $('#student_marital').val(data.student_marital).change();
                        $('#student_email').val(data.student_email);
                        $('#student_address').val(data.student_address);

                        $('#submit_input').show();
                        $('#student_ID').val(student_ID);
                        $('#submit_input').text('Update');
                        $('#submit_input').val('student_update');
                        $('#operation').val("student_update");

                    }
                });


            });

            $(document).on('click', '.delete', function() {
                var student_ID = $(this).attr("id");
                alertify.confirm('Are you sure you want to delete this student?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/student/insert.php",
                            data: {
                                operation: "delete_student",
                                student_ID: student_ID
                            },
                            dataType: 'json',
                            complete: function(data) {
                                $('#delstudent_modal').modal('hide');
                                alertify.alert(data.responseText).setHeader('Delete Student');
                                student_dataTable.ajax.reload();
                            }
                        })
                    },
                    function() {
                        alertify.error('Cancel')
                    }
                ).setHeader('Delete Student');
            });

            // $(document).on('click', '.delete', function() {
            //     var student_ID = $(this).attr("id");
            //     $('#delstudent_modal').modal('show');
            //     // $('.submit').hide();

            //     $('#student_ID').val(student_ID);
            // });

            // $(document).on('click', '#student_delform', function(event) {
            //     var student_ID = $('#student_ID').val();
            //     $.ajax({
            //         type: 'POST',
            //         url: "datatable/student/insert.php",
            //         data: {
            //             operation: "delete_student",
            //             student_ID: student_ID
            //         },
            //         dataType: 'json',
            //         complete: function(data) {
            //             $('#delstudent_modal').modal('hide');
            //             alertify.alert(data.responseText).setHeader('Delete this Student');
            //             student_dataTable.ajax.reload();
            //         }
            //     })
            // });

            $(document).on('click', '.gen_account', function(event) {
                var student_ID = $(this).attr("id");
                alertify.confirm('Are you sure you want to create this person account?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/student/insert.php",
                            data: {
                                operation: "gen_account",
                                student_ID: student_ID
                            },
                            dataType: 'json',
                            complete: function(data) {
                                alertify.alert(data.responseText).setHeader('Generated Account');
                                student_dataTable.ajax.reload();

                            }
                        })

                        student_dataTable.ajax.reload();
                        alertify.success('Ok')
                    },
                    function() {
                        alertify.error('Cancel')
                    }).setHeader('Generate Account');

            });

        });
    </script>
</body>

</html>