<?php
include('../session.php');
require_once("../class-user.php");
$auth_user = new USER();
$page_level = 1;
$auth_user->check_accesslevel($page_level);
$pageTitle = "Manage Room";
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
                    <h1 class="h2">Manage Room</h1>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bcrum bg-dark">
                        <li class="breadcrumb-item"><a href="index" class="bcrum_i_a">Dashboard</a></li>
                        <li class="breadcrumb-item active bcrum_i_ac" aria-current="page">Room</li>
                    </ol>
                </nav>
                <div class="table-responsive">
                    <?php
                    if ($auth_user->student_level() || $auth_user->instructor_level()) {
                    } else {
                    ?>
                        <button type="button" class="btn btn-sm btn-success add">Add Classroom</button>
                        <br><br>
                    <?php
                    }
                    ?>
                    <table class="table table-striped table-sm" id="classroom_data">
                        <thead>
                            <tr>
                                <?php
                                // if ($auth_user->student_level()) {
                                ?>
                                    <th>#</th>
                                    <th></th>
                                    <th>Classroom</th>
                                    <th>Teacher</th>
                                    <th>Semester</th>
                                    <th>Status</th>
                                    <th></th>
                                <?php
                                // } else {
                                ?>
                                    <!-- <th>#</th>
                                    <th>Teacher</th>
                                    <th>Section</th>
                                    <th>School Year</th>
                                    <th>Status</th>
                                    <th></th> -->
                                <?php
                                // }
                                ?>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <!-- Modal -->
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
                                    <!-- <div class="btn-group float-right">
                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#browse_teacher_modal">Browse Teacher</button>
                                    </div>
                                    <br><br> -->
                                    <form method="post" id="classroom_form" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="teacher_name">Teacher Name<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="teacher_name" name="teacher_name" placeholder="" value="" required="" disabled>
                                                    <div class="input-group-append btn-group">
                                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#browse_teacher_modal">Browse Teacher</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6 iii">
                                                <label for="teacher_section">Section<span class="text-danger">*</span></label>
                                                <select class="form-control" id="teacher_section" name="teacher_section">
                                                    <?php
                                                    // $auth_user->ref_section();
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="teacher_semester">School Year<span class="text-danger">*</span></label>
                                                <select class="form-control" id="teacher_semester" name="teacher_semester">
                                                    <?php
                                                    $auth_user->ref_semester();
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="rid_ID" id="rid_ID" />
                                    <input type="hidden" name="room_ID" id="room_ID" />
                                    <input type="hidden" name="operation" id="operation" />
                                    <div class="">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary submit" id="submit_input" value="submit_classroom">Submit</button>
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
                                                <th>Sex</th>
                                                <th>RID</th>
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

                    <div class="modal fade" id="delclassroom_modal" tabindex="-1" role="dialog" aria-labelledby="product_modal_title" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="classroom_modal_title">Delete this classroom</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <!-- <div class="btn-group"> -->
                                        <button type="submit" class="btn btn-danger" id="classroom_delform">Delete</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <!-- </div> -->
                                    </div>
                                </div>
                                <!-- <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="confirm_modal" tabindex="-1" role="dialog" aria-labelledby="product_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirm_modal_title">Confirm</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p id="confirm_modal_content">quoc trong</p>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="teacher_name" id="teacher_name" />
                                    <button type="submit" class="btn btn-primary" id="confirm_btn">Confirm</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                </div>
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
            // get datatable for classroom
            var dataTable = $('#classroom_data').DataTable({
                "processing": true,
                "serverSide": true,
                "ordering":false,
                "order": [],
                "ajax": 
                    <?php
                    echo "{";
                    if ($auth_user->admin_level()) {
                        echo "url: 'datatable/room/fetch.php', type: 'POST'";
                    } else if ($auth_user->student_level()) {
                        echo "url: 'datatable/room/fetch_studentlevel.php', type: 'POST'";
                    } else if ($auth_user->instructor_level()) {
                        echo "url: 'datatable/room/fetch_teacherlevel.php', type: 'POST'";
                    }
                    echo "}"
                    ?>,
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],
            });
            dataTable.columns([1]).visible(false);

            // get datatable for browse teacher
            var teacher_dataTable = $('#teacher_data').DataTable({
                "processing": true,
                "serverSide": true,
                "ordering":false,
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
            teacher_dataTable.columns([1]).visible(false);
            teacher_dataTable.columns([5]).visible(false);


            // get teacher name from browse teacher
            var teach_Rec = '#teacher_data tbody';
            $(teach_Rec).on('click', 'tr', function() {
                var cursor = teacher_dataTable.row($(this)); //get the clicked row
                var data = cursor.data(); // this will give you the data in the current row
                jQuery('#rid_ID').val(data[1]);
                $('#classroom_form').find("input[name='teacher_name'][type='text']").val(data[3]);
                $('#browse_teacher_modal').modal('hide');
                // $.confirm({
                //     title: 'Confirm!',
                //     content: "Are you sure to assign teacher " + data[2] + " for this room?",
                //     columnClass: 'col-md-6',
                //     type: 'red',
                //     animation: 'scale',
                //     animationSpeed : 200,
                //     buttons: {
                //         confirm: function() {
                //             jQuery('#rid_ID').val(data[0])
                //             $('#classroom_form').find("input[name='teacher_name'][type='text']").val(data[2]);
                //             $('#browse_teacher_modal').modal('hide');
                //         },
                //         cancel: function() {
                //         }
                //     }
                // });
            });


            // add new classroom
            $(document).on('click', '.add', function() {
                $('#classroom_modal_title').text('Add Classroom');
                $("#teacher_name").prop("disabled", true);
                $('#classroom_form')[0].reset();
                $('#classroom_modal').modal('show');
                $('#submit_input').show();
                $('#submit_input').text('Submit');
                $('#submit_input').val('add_classroom');
                $('#operation').val("add_classroom");
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
                        $('#classroom_form')[0].reset();
                        $('#classroom_modal').modal('hide');
                        dataTable.ajax.reload();
                    }
                });
            });

            // update classroom
            $(document).on('click', '.edit', function() {
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
                        console.log(data);
                        $('#teacher_name').val(data.teacher_name);
                        $('#teacher_section').val(data.section_ID).change();
                        $('#teacher_semester').val(data.sem_ID).change();
                        $('#rid_ID').val(data.rid_ID);

                        $('#submit_input').show();
                        $('#room_ID').val(room_ID);
                        $('#submit_input').text('Update');
                        $('#submit_input').val('update_classroom');
                        $('#operation').val('update_classroom');
                    }
                });
            });

            // delete class room
            $(document).on('click', '.delete', function() {
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
                                dataTable.ajax.reload();
                                dataTable_product_data.ajax.reload();
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