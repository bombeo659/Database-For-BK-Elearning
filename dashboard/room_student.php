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
                    <h1 class="h2">Student List</h1>
                </div>

                <div class="table-responsive">
                    <?php
                    $room_ID = $_GET["room_ID"];
                    $rtab = "room_student";
                    $rtab_c = "Students";
                    include('x-roomtab.php');
                    ?>

                    <?php if ($auth_user->admin_level()) { ?>
                        <button type="button" class="btn btn-sm btn-success add">
                            Add Student
                        </button>
                        <a href="room_print_student_list?room_ID=<?php echo $room_ID ?>" type="button" class="btn btn-sm btn-info float-right print">
                            Export Student List
                        </a>
                        <br><br>
                    <?php } else if ($auth_user->instructor_level()) { ?>
                        <a href="room_print_student_list?room_ID=<?php echo $room_ID ?>" type="button" class="btn btn-sm btn-info print">
                            Export Student List
                        </a>
                        <br><br>
                    <?php } ?>
                    <table class="table table-striped table-sm" id="student_data">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <div class="modal fade" id="student_modal" tabindex="-1" role="dialog" aria-labelledby="product_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="student_modal_title">Add Student</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="product_modal_content">
                                    <div class="btn-group float-right">
                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#browse_student_modal">Browse Student</button>
                                    </div>
                                    <br><br>
                                    <form method="post" id="student_form" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="a_student_name">Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="a_student_name" name="a_student_name" placeholder="" value="" required="" disabled>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="a_student_ID">Student ID<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="a_student_ID" name="a_student_ID" placeholder="" value="" required="" disabled>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <!-- <input type="hidden" name="res_ID" id="res_ID" /> -->
                                    <input type="hidden" name="rsd_ID" id="rsd_ID" />
                                    <input type="hidden" name="room_ID" id="room_ID" value="<?php echo $this_room_ID ?>" />
                                    <input type="hidden" name="operation" id="operation" />
                                    <div class="">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary submit" id="submit_input" value="submit_student">Submit</button>
                                    </div>
                                </div>
                                    </form>
                                
                            </div>
                        </div>
                    </div>


                    <!-- Modal -->
                    <div class="modal fade" id="browse_student_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Student</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-striped table-sm" id="all_student_data">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th></th>
                                                <th>Student ID</th>
                                                <th>Name</th>
                                                <th>Sex</th>
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

                    <div class="modal fade" id="delstudent_modal" tabindex="-1" role="dialog" aria-labelledby="product_modal_title" aria-hidden="true">
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

            </main>
        </div>
    </div>

    <?php
    include('x-script.php');
    ?>
    <script type="text/javascript">
        $(document).ready(function() {

            var dataTable = $('#student_data').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ordering": false,
                "ajax": {
                    url: "datatable/room/fetch_student.php?room_ID=" + <?php echo $this_room_ID ?>,
                    type: "POST"
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],
            });
            dataTable.columns([1]).visible(false);

            var stud_dataTable = $('#all_student_data').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "bAutoWidth": false,
                "ajax": {
                    url: "datatable/student/fetch.php",
                    type: "POST"
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],

            });
            <?php if ($auth_user->student_level() || $auth_user->instructor_level()) { ?>
                dataTable.columns([6]).visible(false);
            <?php } ?>
            stud_dataTable.columns([1]).visible(false);


            var stud_Rec = '#all_student_data tbody';
            $(stud_Rec).on('click', 'tr', function() {
                var cursor = stud_dataTable.row($(this)); //get the clicked row
                var data = cursor.data(); // this will give you the data in the current row.
                jQuery('#rsd_ID').val(data[1]);
                $('#student_form').find("input[name='a_student_name'][type='text']").val(data[3]);
                $('#student_form').find("input[name='a_student_ID'][type='text']").val(data[2]);
                $('#browse_student_modal').modal('hide');
                // if (confirm("Are you sure you want to use (" + data[2] + ") for this room?")) {
                //     jQuery('#rsd_ID').val(data[0])
                //     $('#student_form').find("input[name='a_student_name'][type='text']").val(data[2]);
                //     $('#browse_student_modal').modal('hide');
                // } else {
                //     return false;
                // }
            });

            $(document).on('click', '.add', function() {
                $('#student_modal_title').text('Add Student');
                $("#student_title").prop("disabled", false);
                $('#student_form')[0].reset();
                $('#student_modal').modal('show');
                $('#submit_input').show();  
                $('#submit_input').text('Submit');
                $('#submit_input').val('submit_student');
                $('#operation').val("submit_student");
            });

            $(document).on('submit', '#student_form', function(event) {
                event.preventDefault();
                var student_ID = $('#rsd_ID').val();
                var room_ID = $('#room_ID').val();
                console.log(student_ID);
                console.log(room_ID);
                $.ajax({
                    url: "datatable/room/insert.php",
                    method: 'POST',
                    // data: new FormData(this) ,
                    data: {
                        operation: "submit_student",
                        rsd_ID: student_ID,
                        room_ID: room_ID
                    },
                    // contentType: false,
                    // processData: false,
                    // dataType: 'json',
                    success: function(data) {
                        alertify.alert(data).setHeader('Student');
                        $('#student_form')[0].reset();
                        $('#student_modal').modal('hide');
                        dataTable.ajax.reload();
                    }
                });
            });


            // $(document).on('click', '.delete', function() {
            //     var student_ID = $(this).attr("id");
            //     $('#delstudent_modal').modal('show');
            //     $('.submit').hide();

            //     $('#res_ID').val(student_ID);
            // });

            $(document).on('click', '.delete', function() {
                var student_ID = $(this).attr("id");
                var room_ID = $('#room_ID').val();
                alertify.confirm('Are you sure you want to delete this student?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/room/insert.php",
                            data: {
                                operation: "delete_student",
                                student_ID: student_ID,
                                room_ID: room_ID
                            },
                            dataType: 'json',
                            complete: function(data) {
                                alertify.alert(data.responseText).setHeader('Delete Student');
                                dataTable.ajax.reload();
                                dataTable_product_data.ajax.reload();
                            }
                        })
                    },
                    function() {
                        alertify.error('Cancel')
                    }
                ).setHeader('Delete Student');
            });

            // $(document).on('click', '#student_delform', function(event) {
            //     var student_ID = $('#res_ID').val();
            //     $.ajax({
            //         type: 'POST',
            //         url: "datatable/room/insert.php",
            //         data: {
            //             operation: "delete_student",
            //             student_ID: student_ID
            //         },
            //         dataType: 'json',
            //         complete: function(data) {
            //             $('#delstudent_modal').modal('hide');
            //             alertify.alert(data.responseText).setHeader('Delete this Student');
            //             dataTable.ajax.reload();
            //             dataTable_product_data.ajax.reload();
            //         }
            //     })
            // });

        });
    </script>
</body>

</html>