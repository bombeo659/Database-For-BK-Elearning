<?php
include('../session.php');
require_once("../class-user.php");
$auth_user = new USER();
$page_level = 3;
$auth_user->check_accesslevel($page_level);
$pageTitle = "Manage Teacher";
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
                    <h1 class="h2">Manage Teacher</h1>

                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bcrum bg-dark">
                        <li class="breadcrumb-item "><a href="index" class="bcrum_i_a">Dashboard</a></li>
                        <li class="breadcrumb-item  active bcrum_i_ac" aria-current="page">Teacher Management</li>
                    </ol>
                </nav>
                <div class="table-responsive">
                    <button type="button" class="btn btn-sm btn-success add">
                        Add Teacher
                    </button>
                    <br><br>
                    <table class="table table-striped table-sm" id="teacher_data">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>Teacher ID</th>
                                <th>Name</th>
                                <th>Sex</th>
                                <!-- <th>Marital</th> -->
                                <th>Account Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>

                    <!--modal teacher -->
                    <div class="modal fade" id="teacher_modal" tabindex="-1" role="dialog" aria-labelledby="teacher_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="teacher_modal_title">Add Attendance</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="post" id="teacher_form" enctype="multipart/form-data">
                                    <div class="modal-body" id="product_modal_content">
                                        <div class="form-row">

                                            <div class="form-group col-md-4">
                                                <img id="s_img" src="../assets/img/users/default.jpg" alt="teacher Image" runat="server" height="125" width="125" class="img-thumbnail" style="border:1px solid; border-color: #4caf50; min-width:125px; min-height:125px; max-width:125px; max-height:125px; background-size:cover;" />
                                                <br><br>
                                                <input type="file" class="form-control" id="teacher_img" name="teacher_img" placeholder="" value="">
                                            </div>
                                            <div class="form-group col-md-4">

                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="teacher_EmpID">Teacher ID<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="teacher_EmpID" name="teacher_EmpID" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="teacher_fname">First Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="teacher_fname" name="teacher_fname" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="teacher_mname">Middle Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="teacher_mname" name="teacher_mname" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="teacher_lname">Last Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="teacher_lname" name="teacher_lname" placeholder="" value="" required="">
                                            </div>
                                            <!-- <div class="form-group col-md-3">
                                                <label for="teacher_suffix">Suffix<span class="text-danger">*</span></label>
                                                <select class="form-control" id="teacher_suffix" name="teacher_suffix">
                                                    <?php
                                                    // $auth_user->user_suffix_option();
                                                    ?>
                                                </select>
                                            </div> -->
                                            <div class="form-group col-md-6">
                                                <label for="teacher_bday">Birthday<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="teacher_bday" name="teacher_bday" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="teacher_sex">Sex<span class="text-danger">*</span></label>
                                                <select class="form-control" id="teacher_sex" name="teacher_sex" required="">
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Orther">Other</option>
                                                    <?php
                                                    // $auth_user->user_sex_option();
                                                    ?>
                                                </select>
                                            </div>
                                            <!-- <div class="form-group col-md-4">
                                                <label for="teacher_marital">Marital<span class="text-danger">*</span></label>
                                                <select class="form-control" id="teacher_marital" name="teacher_marital" required="">
                                                    <?php
                                                    // $auth_user->user_marital_option();
                                                    ?>
                                                </select>
                                            </div> -->
                                            <div class="form-group col-md-12">
                                                <label for="teacher_email">Email<span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="teacher_email" name="teacher_email" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="teacher_address">Address<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="teacher_address" name="teacher_address" placeholder="" value="" required="">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="teacher_ID" id="teacher_ID" />
                                        <input type="hidden" name="operation" id="operation" />
                                        <div class="btn-group" id='sbtng'>
                                            <button type="button" class="btn rounded mr-1 btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn rounded btn-primary submit" id="submit_input" value="submit_teacher">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--/modal teacher -->

                    <!--delete modal -->
                    <div class="modal fade" id="delteacher_modal" tabindex="-1" role="dialog" aria-labelledby="teacher_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="teacher_modal_title">Delete this Teacher</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-danger" id="teacher_delform">Delete</button>
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

        $("#teacher_img").change(function() {
            readURL(this);
        });
        $(document).ready(function() {

            var teacher_dataTable = $('#teacher_data').DataTable({
                "processing": true,
                "serverSide": true,
                "ordering": false,
                "order": [],
                "ajax": {
                    url: "datatable/teacher/fetch.php",
                    type: "POST"
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],

            });
            teacher_dataTable.columns([1]).visible(false);


            $(document).on('submit', '#teacher_form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: "datatable/teacher/insert.php",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alertify.alert(data).setHeader('teacher Record');
                        $('#teacher_form')[0].reset();
                        $('#teacher_modal').modal('hide');
                        teacher_dataTable.ajax.reload();
                    }
                });

            });

            $(document).on('click', '.add', function() {
                $('#teacher_modal_title').text('Add New teacher');
                $('#teacher_modal').modal('show');
                $('#teacher_form')[0].reset();

                var btng = document.getElementById("sbtng");
                btng.className = btng.className.replace(/\btng_null\b/g, "");
                btng.classList.add("btn-group");

                $('#s_img').attr('src', "../assets/img/users/default.jpg");
                $("#teacher_EmpID").prop("disabled", false);
                $("#teacher_fname").prop("disabled", false);
                $("#teacher_mname").prop("disabled", false);
                $("#teacher_lname").prop("disabled", false);
                // $("#teacher_suffix").prop("disabled", false);
                $("#teacher_bday").prop("disabled", false);
                $("#teacher_sex").prop("disabled", false);
                // $("#teacher_marital").prop("disabled", false);
                $("#teacher_email").prop("disabled", false);
                $("#teacher_address").prop("disabled", false);

                $("#teacher_img").show();
                $('#submit_input').show();

                $('#submit_input').text('Submit');
                $('#submit_input').val('submit_teacher');
                $('#operation').val("submit_teacher");
            });

            $(document).on('click', '.view', function() {
                var teacher_ID = $(this).attr("id");

                $('#teacher_modal_title').text('View Teacher');
                $('#teacher_modal').modal('show');

                $('#submit_input').hide();
                var btng = document.getElementById("sbtng");
                btng.className = btng.className.replace(/\bbtn-group\b/g, "");
                btng.classList.add("btng_null");

                $("#teacher_img").hide();

                $.ajax({
                    url: "datatable/teacher/fetch_single.php",
                    method: 'POST',
                    data: {
                        action: "teacher_view",
                        teacher_ID: teacher_ID
                    },
                    dataType: 'json',
                    success: function(data) {
                        $("#teacher_EmpID").prop("disabled", true);
                        $("#teacher_fname").prop("disabled", true);
                        $("#teacher_mname").prop("disabled", true);
                        $("#teacher_lname").prop("disabled", true);
                        // $("#teacher_suffix").prop("disabled", true);
                        $("#teacher_bday").prop("disabled", true);
                        $("#teacher_sex").prop("disabled", true);
                        // $("#teacher_marital").prop("disabled", true);
                        $("#teacher_email").prop("disabled", true);
                        $("#teacher_address").prop("disabled", true);

                        $('#s_img').attr('src', data.teacher_img);
                        $('#teacher_EmpID').val(data.teacher_EmpID);
                        $('#teacher_fname').val(data.teacher_fname);
                        $('#teacher_mname').val(data.teacher_mname);
                        $('#teacher_lname').val(data.teacher_lname);
                        // $('#teacher_suffix').val(data.teacher_suffix).change();
                        $('#teacher_bday').val(data.teacher_bday);
                        $('#teacher_sex').val(data.teacher_sex).change();
                        // $('#teacher_marital').val(data.teacher_marital).change();
                        $('#teacher_email').val(data.teacher_email);
                        $('#teacher_address').val(data.teacher_address);

                        $('#submit_input').hide();
                        $('#teacher_ID').val(teacher_ID);
                        $('#submit_input').text('Update');
                        $('#submit_input').val('teacher_view');
                        $('#operation').val("teacher_view");
                    }
                });
            });


            $(document).on('click', '.edit', function() {
                var teacher_ID = $(this).attr("id");
                var acreg = $(this).attr("acreg");
                $('#teacher_modal_title').text('View teacher');
                $('#teacher_modal').modal('show');

                var btng = document.getElementById("sbtng");
                btng.className = btng.className.replace(/\btng_null\b/g, "");
                btng.classList.add("btn-group");

                $("#teacher_img").show();

                $.ajax({
                    url: "datatable/teacher/fetch_single.php",
                    method: 'POST',
                    data: {
                        action: "teacher_update",
                        teacher_ID: teacher_ID
                    },
                    dataType: 'json',
                    success: function(data) {
                        // if (acreg == "UN") {
                        //     $("#teacher_EmpID").prop("disabled", false);
                        // } else {
                        //     $("#teacher_EmpID").prop("disabled", true);
                        // }
                        
                        $("#teacher_EmpID").prop("disabled", false);
                        $("#teacher_fname").prop("disabled", false);
                        $("#teacher_mname").prop("disabled", false);
                        $("#teacher_lname").prop("disabled", false);
                        $("#teacher_suffix").prop("disabled", false);
                        $("#teacher_bday").prop("disabled", false);
                        $("#teacher_sex").prop("disabled", false);
                        $("#teacher_marital").prop("disabled", false);
                        $("#teacher_email").prop("disabled", false);
                        $("#teacher_address").prop("disabled", false);

                        $('#s_img').attr('src', data.teacher_img);
                        $('#teacher_EmpID').val(data.teacher_EmpID);
                        $('#teacher_fname').val(data.teacher_fname);
                        $('#teacher_mname').val(data.teacher_mname);
                        $('#teacher_lname').val(data.teacher_lname);
                        $('#teacher_suffix').val(data.teacher_suffix).change();
                        $('#teacher_bday').val(data.teacher_bday);
                        $('#teacher_sex').val(data.teacher_sex).change();
                        $('#teacher_marital').val(data.teacher_marital).change();
                        $('#teacher_email').val(data.teacher_email);
                        $('#teacher_address').val(data.teacher_address);

                        $('#submit_input').show();
                        $('#teacher_ID').val(teacher_ID);
                        $('#submit_input').text('Update');
                        $('#submit_input').val('teacher_update');
                        $('#operation').val("teacher_update");
                    }
                });
            });

            $(document).on('click', '.delete', function() {
                var teacher_ID = $(this).attr("id");
                alertify.confirm('Are you sure you want to delete this teacher?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/teacher/insert.php",
                            data: {
                                operation: "delete_teacher",
                                teacher_ID: teacher_ID
                            },
                            dataType: 'json',
                            complete: function(data) {
                                $('#delteacher_modal').modal('hide');
                                alertify.alert(data.responseText).setHeader('Delete Teacher');
                                teacher_dataTable.ajax.reload();
                            }
                        })
                    },
                    function() {
                        alertify.error('Cancel')
                    }
                ).setHeader('Delete Teacher');
            });

            // $(document).on('click', '.delete', function() {
            //     var teacher_ID = $(this).attr("id");
            //     $('#delteacher_modal').modal('show');
            //     // $('.submit').hide();

            //     $('#teacher_ID').val(teacher_ID);
            // });

            // $(document).on('click', '#teacher_delform', function(event) {
            //     var teacher_ID = $('#teacher_ID').val();
            //     $.ajax({
            //         type: 'POST',
            //         url: "datatable/teacher/insert.php",
            //         data: {
            //             operation: "delete_teacher",
            //             teacher_ID: teacher_ID
            //         },
            //         dataType: 'json',
            //         complete: function(data) {
            //             $('#delteacher_modal').modal('hide');
            //             alertify.alert(data.responseText).setHeader('Delete this teacher');
            //             teacher_dataTable.ajax.reload();

            //         }
            //     })

            // });

            $(document).on('click', '.gen_account', function(event) {
                var teacher_ID = $(this).attr("id");
                alertify.confirm('Are you sure you want to create this person account?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/teacher/insert.php",
                            data: {
                                operation: "gen_account",
                                teacher_ID: teacher_ID
                            },
                            dataType: 'json',
                            complete: function(data) {
                                alertify.alert(data.responseText).setHeader('Generated Account');
                                teacher_dataTable.ajax.reload();
                            }
                        })
                        teacher_dataTable.ajax.reload();
                        // alertify.success('Ok')
                    },
                    function() {
                        alertify.error('Cancel')
                    }
                ).setHeader('Generate Account');
            });
        });
    </script>
</body>

</html>