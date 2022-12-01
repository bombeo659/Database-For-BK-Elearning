<?php
include('../session.php');
require_once("../class-user.php");
$auth_user = new USER();
$page_level = 3;
$auth_user->check_accesslevel($page_level);
$pageTitle = "Manage Classrooom";
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
                    <h1 class="h2">Manage Classroom</h1>

                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bcrum bg-dark">
                        <li class="breadcrumb-item "><a href="index" class="bcrum_i_a">Dashboard</a></li>
                        <li class="breadcrumb-item  active bcrum_i_ac" aria-current="page">Classroom Management</li>
                    </ol>
                </nav>

                <div class="table-responsive">
                    <!-- <div class=""> -->
                        <button type="button" class="btn btn-sm btn-success add">
                            Add Subject
                        </button>
                        <!-- <button type="button" class="btn btn-sm btn-info add_room">
                            Add Classroom
                        </button> -->
                    <!-- </div> -->
                    <br><br>
                    <table class="table table-striped table-sm" id="subject_data">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>Subject</th>
                                <th>Faculty</th>
                                <th>School Year</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>


                    <div class="modal fade" id="subject_modal" tabindex="-1" role="dialog" aria-labelledby="product_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="subject_modal_title">Add Subject</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="product_modal_content">

                                    <form method="post" id="subject_form" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="subject_title">Subject<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="subject_title" name="subject_title" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="subject_faculty">Faculty<span class="text-danger">*</span></label>
                                                <select class="form-control" id="subject_faculty" name="subject_faculty">
                                                <?php
                                                    $auth_user->user_faculty_option();
                                                ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="subject_semester">School Year<span class="text-danger">*</span></label>
                                                <select class="form-control" id="subject_semester" name="subject_semester">
                                                    <?php
                                                    $auth_user->ref_semester();
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="subject_ID" id="subject_ID" />
                                            <input type="hidden" name="operation" id="operation" />
                                            <div class="">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary submit" id="submit_input" value="submit_subject">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal -->
                    <div class="modal fade" id="room" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Room</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <button type="button" class="btn btn-sm btn-success add_room mb-2">
                                        Add Classroom
                                    </button>
                                    <table class="table table-striped table-sm" id="subjectroom_data">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Teacher</th>
                                                <th>Status</th> 
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="classroom_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="classroom_modal_title" id="classroom_modal_title">Add Classroom</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" id="classroom_form" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="class_name">Class Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="class_name" name="class_name" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="teacher_name">Teacher Name<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="teacher_name" name="teacher_name" placeholder="" value="" required="" disabled>
                                                    <div class="input-group-append btn-group">
                                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#browse_teacher_modal">Browse Teacher</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="room_subject">Subject<span class="text-danger">*</span></label>
                                                <select class="form-control" id="room_subject" name="room_subject" >
                                                    <?php
                                                    $auth_user->ref_subject();
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="subject_ID" id="subject_room_ID" />
                                    <input type="hidden" name="rid_ID" id="rid_ID" />
                                    <input type="hidden" name="room_ID" id="room_ID" />
                                    <input type="hidden" name="operation" id="operation_room" />
                                    <div class="">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary submit" id="room_submit_input" value="submit_classroom">Submit</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="browse_teacher_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Teacher</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-striped table-sm" id="teacher_data">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th></th>
                                                <th>Teacher ID</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="modal fade" id="delsubject_modal" tabindex="-1" role="dialog" aria-labelledby="product_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="subject_modal_title">Delete this subject</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-danger" id="subject_delform">Delete</button>
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

            </main>
        </div>
    </div>

    <?php
    include('x-script.php');
    ?>
    <script type="text/javascript">
        $(document).ready(function() {

            var dataTable = $('#subject_data').DataTable({
                "processing": true,
                "serverSide": true,
                "ordering" : false,
                "order": [],
                "ajax": {
                    url: "datatable/subject/fetch.php",
                    type: "POST"
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],

            });
            dataTable.columns([1]).visible(false);

            function subjectroom(subject_ID) {
                var room_dataTable = $('#subjectroom_data').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ordering" : false,
                    "order": [],
                    "bAutoWidth": false,
                    "ajax": {
                        url: "datatable/room/fetch.php?sub_id=" + subject_ID,
                        type: "POST"
                    },
                    "columnDefs": [{
                        "targets": [0],
                        "orderable": false,
                    }, ],

                });
                room_dataTable.columns([1]).visible(false);
            }
            
            var teacher_dataTable = $('#teacher_data').DataTable({
                "processing": true,
                "serverSide": true,
                "ordering" : false,
                "order": [],
                "bAutoWidth": false,
                "ajax": {
                    url: "datatable/teacher/fetch.php",
                    type: "POST"
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],

            });
            // $('#teacher_data').DataTable().destroy();
            teacher_dataTable.columns([1]).visible(false);

            $(document).on('submit', '#subject_form', function(event) {
                event.preventDefault();
                console.log(new FormData(this));
                $.ajax({
                    url: "datatable/subject/insert.php",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alertify.alert(data).setHeader('Add Subject');
                        $('#subject_form')[0].reset();
                        $('#subject_modal').modal('hide');
                        dataTable.ajax.reload();
                    }
                });

            });

            $(document).on('click', '.add', function() {
                $('#subject_modal_title').text('Add Subject');
                $("#subject_title").prop("disabled", false);
                $('#subject_form')[0].reset();
                $('#subject_modal').modal('show');
                $('#submit_input').show();
                $('#submit_input').text('Submit');
                $('#submit_input').val('submit_subject');
                $('#operation').val("submit_subject");
            });

            $(document).on('click', '.view', function() {
                var subject_ID = $(this).attr("id");
                $('#subject_modal_title').text('View subject');
                $('#subject_modal').modal('show');
                $("#submit_input").hide();

                $.ajax({
                    url: "datatable/subject/fetch_single.php",
                    method: 'POST',
                    data: {
                        action: "subject_view",
                        subject_ID: subject_ID
                    },
                    dataType: 'json',
                    success: function(data) {
                        $("#subject_title").prop("disabled", true);
                        $('#subject_title').val(data.subject_Name);
                        $('#subject_faculty').val(data.faculty_ID).change();
                        $('#subject_semester').val(data.semester_ID).change();
                        $('#submit_input').hide();
                        $('#subject_ID').val(subject_ID);
                        $('#submit_input').text('View');
                        $('#submit_input').val('subject_view');
                        $('#operation').val("subject_view");
                    }
                });
            });


            $(document).on('click', '.edit', function() {
                var subject_ID = $(this).attr("id");
                $('#subject_modal_title').text('Edit Subject');
                $('#subject_modal').modal('show');
                $("#submit_input").show();

                $.ajax({
                    url: "datatable/subject/fetch_single.php",
                    method: 'POST',
                    data: {
                        action: "subject_view",
                        subject_ID: subject_ID
                    },
                    dataType: 'json',
                    success: function(data) {
                        $("#subject_title").prop("disabled", false);
                        $('#subject_title').val(data.subject_Name);
                        $('#subject_faculty').val(data.faculty_ID).change();
                        $('#subject_semester').val(data.semester_ID).change();
                        $('#submit_input').show();
                        $('#subject_ID').val(subject_ID);
                        $('#submit_input').text('Update');
                        $('#submit_input').val('subject_update');
                        $('#operation').val("subject_edit");
                    }
                });
            });

            $(document).on('click', '.delete', function() {
                var subject_ID = $(this).attr("id");
                alertify.confirm('Are you sure you want to delete this subject?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/subject/insert.php",
                            data: {
                                operation: "delete_subject",
                                subject_ID: subject_ID
                            },
                            dataType: 'json',
                            complete: function(data) {
                                alertify.alert(data.responseText).setHeader('Delete this subject');
                                dataTable.ajax.reload();
                            }
                        })
                    },
                    function() {
                        alertify.error('Cancel')
                    }
                ).setHeader('Delete Subject');
            });

            

            $(document).on('click', '.room', function() {
                var subject_ID = $(this).attr("id");
                $('#subjectroom_data').DataTable().destroy();
                $('.add_room').attr('id', subject_ID);
                subjectroom(subject_ID);
            });

            var teach_Rec = '#teacher_data tbody';
            $(teach_Rec).on('click', 'tr', function() {
                var cursor = teacher_dataTable.row($(this)); //get the clicked row
                var data = cursor.data(); // this will give you the data in the current row
                jQuery('#rid_ID').val(data[1]);
                $('#classroom_form').find("input[name='teacher_name'][type='text']").val(data[3]);
                $('#browse_teacher_modal').modal('hide');
            });


            $(document).on('submit', '#classroom_form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: "datatable/room/insert.php",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alertify.alert(data).setHeader('Classroom');
                        // console.log($('#subject_room_ID').val());
                        $('#subjectroom_data').DataTable().destroy();
                        subjectroom($('#subject_room_ID').val());
                        $('#classroom_form')[0].reset();
                        $('#classroom_modal').modal('hide');
                        // room_dataTable.ajax.reload();
                    }
                });

            });

            $(document).on('click', '.add_room', function() {
                $('#classroom_modal_title').text('Add Classroom');
                $("#teacher_name").prop("disabled", true);
                $('#classroom_form')[0].reset();

                $('#room_subject').val($(this).attr('id')).change();
                $('#room_subject').prop("disabled", true);
                $('#subject_room_ID').val($(this).attr('id'));

                $('#classroom_modal').modal('show');
                $('#room_submit_input').show();
                $('#room_submit_input').text('Submit');
                $('#room_submit_input').val('submit_classroom');
                $('#operation_room').val("add_classroom");
            });

            // update classroom
            $(document).on('click', '.edit-room', function() {
                var room_ID = $(this).attr("id");
                $('#classroom_modal_title').text('Edit Classroom');
                $('#classroom_modal').modal('show');
                $.ajax({
                    url: "datatable/room/fetch_single.php",
                    method: 'POST',
                    data: {
                        action: "update_classroom",
                        room_ID: room_ID
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#teacher_name').val(data.teacher_name);
                        $('#class_name').val(data.class_name);
                        $('#room_subject').val(data.subject_id).change();
                        $('#room_subject').prop("disabled", true);
                        $('#subject_room_ID').val(data.subject_id).change();
                        $('#rid_ID').val(data.ind_id);

                        $('#submit_input').show();
                        $('#room_ID').val(room_ID);
                        $('#room_submit_input').text('Update');
                        $('#room_submit_input').val('update_classroom');
                        $('#operation_room').val('update_classroom');
                    }
                });
            });

            // delete class room
            $(document).on('click', '.delete-room', function() {
                var room_ID = $(this).attr("id");
                alertify.confirm('Are you sure you want to delete this classroom?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/room/insert.php",
                            data: {
                                operation: "delete_classroom",
                                room_ID: room_ID
                            },
                            dataType: 'json',
                            complete: function(data) {
                                alertify.alert(data.responseText).setHeader('Delete Classroom');
                                $('#subjectroom_data').DataTable().destroy();
                                subjectroom($('.add_room').attr("id"));
                            }
                        })
                    },
                    function() {
                        alertify.error('Cancel')
                    }
                ).setHeader('Delete Classroom');
            });
        });
    </script>
</body>

</html>