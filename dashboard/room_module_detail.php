<?php
include('../session.php');
require_once("../class-user.php");

$auth_user = new USER();
$page_level = 1;
$auth_user->check_accesslevel($page_level);
$pageTitle = "Manage Module";
$mod_ID = "";
if (isset($_REQUEST['mod_ID'])) {
    $mod_ID = $_REQUEST['mod_ID'];
    $this_room_ID = $_REQUEST['room_ID'];
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
                    <h1 class="h2"> <?php echo $auth_user->get_module_title($mod_ID) ?> </h1>
                </div>

                <div class="table-responsive">
                    <?php
                    $room_ID = $_GET["room_ID"];
                    $rtab = "room_module";
                    $rtab_c = "Modules";
                    // include('x-roomtab.php');
                    echo '<a type="button" class="btn btn-sm btn-secondary" 
                        href="room_module?room_ID=' . $room_ID . '">Back</a>';
                    ?>
                    <button type="button" class="btn btn-sm btn-success add">
                        Add Topic
                    </button>
                    <button type="button" class="btn btn-sm btn-info float-right material">Module Files</button>
                    <br>
                    <table class="table table-borderless table-sm" id="topic_data">
                        <thead>
                            <tr>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
                <!-- Modal -->
                <div class="modal fade" id="m_subtopic" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="m_subtopic_title">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="m_subtopic_content">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="m_topic" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="m_subtopic_title">Add Topic</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="m_subtopic_content">
                                <form method="post" id="topic_form" enctype="multipart/form-data">
                                    <div class="form-row">

                                        <div class="form-group col-md-12">
                                            <label for="topic_title">Topic<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="topic_title" name="topic_title" placeholder="" value="" required="">
                                        </div>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="operation" id="operation">
                                <input type="hidden" name="topic_ID" id="topic_ID">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="submit_t">Submit</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>


                <!-- Modal -->
                <div class="modal fade" id="m_subtopicx" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="m_subtopic_title">Add Subtopic</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="m_subtopic_content">
                                <form method="post" id="subtopic_form" enctype="multipart/form-data">
                                    <div class="form-row">

                                        <div class="form-group col-md-12">
                                            <label for="subtopic_title">Topic<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="subtopic_title" name="subtopic_title" placeholder="" value="" required="">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="subtopic_content">Content<span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="subtopic_content" name="subtopic_content" value="" required=""></textarea>
                                        </div>

                                    </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="operation1" id="operation1">
                                <input type="hidden" name="submtop_ID" id="submtop_ID">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="submit_tt">Submit</button>
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
                                <h5 class="modal-title" id="exampleModalLabel">Module Files</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <?php
                                if ($auth_user->student_level()) {
                                } else {
                                ?>
                                <button type="button" class="btn btn-sm btn-success add_materials">Upload Files</button>
                                <?php
                                }
                                ?>
                                <br><br>
                                <table class="table table-striped table-sm" id="material_data">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th width="60%">Name</th>
                                            <th>Type</th>
                                            <th></th>
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

                <!-- Modal -->
                <div class="modal fade" id="material_submit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog " role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Upload Files</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="post" id="material_form" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="material_name" class="col-sm-3 col-form-label">File Name:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="material_name" name="material_name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="material_file" class="col-sm-3 col-form-label">File Upload:</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control" id="material_file" name="material_file">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="room_ID" id="room_ID" value="<?php echo $room_ID ?>" />
                                    <input type="hidden" name="mod_ID" id="module_IDx" />
                                    <input type="hidden" name="m_operation" id="m_operation" value="material_submit"/>

                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary submit" id="submit_input_m" value="material_submit">Submit</button>
                                </div>
                            </form>
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
            var dataTable = $('#topic_data').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "bAutoWidth": false,
                "searching": false,
                //   "ordering": false,
                "info": false,
                "paging": false,
                "ajax": {
                    url: "datatable/room_module/fetch_topic.php?mod_ID=<?php echo $mod_ID ?>",
                    type: "POST"
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],

            });

            $(document).on('submit', '#topic_form', function(event) {
                event.preventDefault();

                var topic_ID = $("#topic_ID").val();
                var topic_title = $("#topic_title").val();

                var operation = $('#operation').val();
                // alert(topic_title);
                $.ajax({
                    url: "load1.php",
                    method: 'POST',
                    data: {
                        action: operation,
                        mod_ID: <?php echo $mod_ID ?>,
                        topic_title: topic_title,
                        topic_ID: topic_ID
                    },
                    dataType: "json",
                    complete: function(data) {
                        dataTable.ajax.reload();
                        $('#m_topic').modal("hide");
                    }
                });
            });

            $(document).on('submit', '#subtopic_form', function(event) {
                event.preventDefault();

                var subtopic_title = $("#subtopic_title").val();
                var subtopic_content = $("#subtopic_content").val();
                var topic_ID = $("#topic_ID").val();
                var submtop_ID = $("#submtop_ID").val();
                var operation1 = $('#operation1').val();
                
                $.ajax({
                    url: "load1.php",
                    method: 'POST',
                    data: {
                        action: operation1,
                        mod_ID: <?php echo $mod_ID ?>,
                        topic_ID: topic_ID,
                        submtop_ID: submtop_ID,
                        subtopic_title: subtopic_title,
                        subtopic_content: subtopic_content
                    },
                    dataType: "json",
                    complete: function(data) {

                        dataTable.ajax.reload();
                        $('#m_subtopicx').modal("hide");
                    }
                });

            });

            $(document).on('click', '.add', function() {
                $('#m_topic').modal("show");
                $('#operation').val("add_topic");
                $('#submit_t').text("Submit");

                $('#topic_form')[0].reset();
            });


            $(document).on('click', '.edit_topic', function() {
                var topic_ID = $(this).attr("id");
                $('#operation').val("update_topic");
                $('#submit_t').text("Update");
                $('#topic_ID').val(topic_ID);

                $.ajax({
                    url: "load1.php",
                    method: 'POST',
                    data: {
                        action: "get_topic",
                        topic_ID: topic_ID
                    },
                    // contentType:false,
                    // processData:false,   
                    dataType: "json",
                    success: function(data) {

                        $('#m_topic').modal("show");
                        $("#topic_ID").val(data.mtopic_ID);
                        $("#topic_title").val(data.mtopic_Title);
                        dataTable.ajax.reload();
                    }
                });
            });

            $(document).on('click', '.delete_topic', function() {

                var topic_ID = $(this).attr("id");
                if (confirm("Are you sure you want to delete this topic?")) {
                    $.ajax({
                        url: "load1.php",
                        method: 'POST',
                        data: {
                            action: "delete_topic",
                            topic_ID: topic_ID
                        },
                        dataType: "json",
                        complete: function(data) {
                            dataTable.ajax.reload();
                        }
                    });
                } else {
                    return false;
                }
            });


            $(document).on('click', '.add_subtopic', function() {
                var topicID = $(this).attr("id");
                // alert(subtopicID);
                $("#topic_ID").val(topicID);
                $('#m_subtopicx').modal("show");

                $('#subtopic_form')[0].reset();

                $('#operation1').val("add_subtopic");
                $('#submit_tt').text("Submit");

            });

            $(document).on('click', '.view_subtopic', function() {
                var subtopic_ID = $(this).attr("sub-topic");
                $('#m_subtopic').modal("show");
                $.ajax({
                    url: "load1.php",
                    method: 'POST',
                    data: {
                        action: "get_subtopic",
                        submtop_ID: subtopic_ID
                    },
                    dataType: "json",
                    success: function(data) {
                        $("#m_subtopic_title").html(data.submtop_Title);
                        $("#m_subtopic_content").html(data.submtop_Content);
                    }
                });
            });

            $(document).on('click', '.edit_subtopic', function() {
                var subtopicID = $(this).attr("sub-topic");
                // alert("Edit:"+subtopicID);

                $('#m_subtopicx').modal("show");
                $('#operation1').val("update_subtopic");
                $('#submit_tt').text("Update");
                $('#submtop_ID').val(subtopicID);

                $.ajax({
                    url: "load1.php",
                    method: 'POST',
                    data: {
                        action: "get_subtopic",
                        submtop_ID: subtopicID
                    },
                    dataType: "json",
                    success: function(data) {
                        $("#subtopic_title").val(data.submtop_Title);
                        $("#subtopic_content").val(data.submtop_Content);
                    }
                });
            });
            $(document).on('click', '.delete_subtopic', function() {
                var subtopicID = $(this).attr("sub-topic");
                if (confirm("Are you sure you want to delete this subtopic?")) {
                    $.ajax({
                        url: "load1.php",
                        method: 'POST',
                        data: {
                            action: "delete_subtopic",
                            subtopicID: subtopicID
                        },
                        dataType: "json",
                        complete: function(data) {
                            dataTable.ajax.reload();
                        }
                    });
                } else {
                    return false;
                }
            });

            $(document).on('click', '.material', function() {
                // var module_ID = $(this).attr("id");
                var module_ID = <?php echo $mod_ID ?>;
                // $('#module_modal_title').text('View Module');
                $('#material_modal').modal('show');
                // $("#submit_input").hide();

                $('#module_IDx').val(module_ID);
                material_data(module_ID);
                // $('#material_data').DataTable().destroy();

                // $.ajax({
                //    url:"datatable/room_module/fetch_single.php",
                //    method:'POST',
                //    data:{action:"module_view",module_ID:module_ID},
                //    dataType    :   'json',
                //    success:function(data)
                //    {

                //    $("#module_title").prop("disabled", true);

                //      $('#module_title').val(data.mod_Title);

                //      $('#submit_input').hide();
                //      $('#module_ID').val(module_ID);
                //      $('#submit_input').text('View');
                //      $('#submit_input').val('module_view');
                //      $('#operation').val("module_view");

                //    }
                //  });
            });

            //************MATERIAL SCRIPT************//
            function material_data(mod_ID) {
                var materials_dataTable = $('#material_data').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "bAutoWidth": false,
                    "order": [],
                    "ajax": {
                        url: "datatable/room_materials/fetch.php?room_ID=" + <?php echo $room_ID ?> + "&mod_ID=" + mod_ID,
                        type: "POST"
                    },
                    "columnDefs": [{
                        "targets": [0],
                        "orderable": false,
                    }, ],

                });

                $(document).on('submit', '#material_form', function(event) {
                    event.preventDefault();

                    $.ajax({
                        url: "datatable/room_materials/insert.php",
                        method: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            alertify.alert(data).setHeader('Room Files');
                            $('#material_form')[0].reset();
                            $('#material_submit_modal').modal('hide');
                            materials_dataTable.ajax.reload();
                        }
                    });

                });

                $(document).on('click', '.add_materials', function() {

                    $('#material_submit_modal').modal('show');
                    materials_dataTable.ajax.reload();
                    // questionaire_dataTable.ajax.reload();

                });

                $(document).on('click', '.delete_material', function() {
                    var material_ID = $(this).attr("id");

                    alertify.confirm('Are you sure you want to delete this file?',
                        function() {
                            $.ajax({
                                type: 'POST',
                                url: "datatable/room_materials/insert.php",
                                data: {
                                    m_operation: "material_delete",
                                    material_ID: material_ID
                                },
                                dataType: 'json',
                                complete: function(data) {
                                    alertify.alert(data.responseText).setHeader('Room Files');
                                    materials_dataTable.ajax.reload();
                                }
                            })
                        },
                        function() {
                            alertify.error('Cancel')
                        }
                    ).setHeader('Room Files');
                });
            }

        });
    </script>
</body>

</html>