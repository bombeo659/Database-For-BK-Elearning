<?php
include('../session.php');
require_once("../class-user.php");

$auth_user = new USER();
$page_level = 1;
$auth_user->check_accesslevel($page_level);

$pageTitle = "Manage Classroom";
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
                    <h1 class="h2">Announcement Board</h1>
                </div>

                <div class="table-responsive">
                    <?php
                    if (isset($_GET["room_ID"])) {
                        $room_ID = $_GET["room_ID"];
                    }
                    // if ($auth_user->student_level()) {
                    //     $room_ID = 1;
                    // }
                    $rtab = "room_announcement";
                    $rtab_c = "Announcement";
                    include('x-roomtab.php');
                    ?>
                    <button type="button" class="btn btn-sm btn-success add">
                        Add Post
                    </button>
                    <br><br>
                    <table class="table table-striped table-sm" id="post_data">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th width="40%">Title</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <!-- modal add new post -->
                    <div class="modal fade" id="post_modal" tabindex="-1" role="dialog" aria-labelledby="product_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="post_modal_title">Add Post</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="product_modal_content">
                                    <form method="post" id="post_form" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="post_title">Title<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="post_title" name="post_title" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <textarea class="form-control" id="post_content" name="post_content" placeholder="Description..." value="" required=""></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="post_ID" id="post_ID" />
                                            <input type="hidden" name="room_ID" id="room_ID" value="<?php echo $room_ID ?>" />
                                            <input type="hidden" name="operation" id="operation" />
                                            <div class="">
                                                <button type="button" class="btn btn-secondary" id="submit_x" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary submit" id="submit_input" value="submit_section">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div id="post_comment">
                                        <h5>Comment</h5>
                                        <table class="table table-striped table-sm" id="comment_data">
                                            <tbody></tbody>
                                        </table>
                                        <form method="post" id="comment_form" enctype="multipart/form-data">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Comment here ..." aria-label="aaa" id="comment_box" aria-describedby="basic-addon2">
                                                <div class="input-group-append">
                                                    <input type="submit"  class="btn btn-secondary" id="send_comment" value="SEND">
                                                </div>
                                            </div>
                                        </form>
                                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="delpost_modal" tabindex="-1" role="dialog" aria-labelledby="product_modal_title" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="post_modal_title">Delete this post</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-danger" id="post_delform">Delete</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                                <!-- <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div> -->
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
            var dataTable = $('#post_data').DataTable({
                "processing": true,
                "serverSide": true,
                "ordering": false,
                "order": [],
                "ajax": {
                    url: "datatable/room_announcement/fetch.php?room_ID=<?php echo $room_ID ?>",
                    type: "POST"
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],
            });

            function comment_data(post_ID) {
                var dataTable = $('#comment_data').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "bAutoWidth": false,
                    "order": [],
                    "ajax": {
                        url: "datatable/room_comment/fetch.php?post_ID=" + post_ID,
                        type: "POST"
                    },
                    "columnDefs": [{
                        "targets": [0],
                        "orderable": false,
                    }, ],
                });
            }

            $(document).on('submit', '#post_form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: "datatable/room_announcement/insert.php",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alertify.alert(data).setHeader('Post');
                        $('#post_form')[0].reset();
                        $('#post_modal').modal('hide');
                        dataTable.ajax.reload();
                    }
                });
            });

            $(document).on('click', '.add', function() {
                $('#post_modal_title').text('Add Post');
                $("#post_title").prop("disabled", false);
                $("#post_content").prop("disabled", false);
                $('#post_form')[0].reset();
                $('#post_modal').modal('show');

                $('#submit_input').show();
                $('#submit_x').show();
                $('#post_comment').hide();

                $('#submit_input').text('Submit');
                $('#submit_input').val('post_submit');
                $('#operation').val("post_submit");
            });

            $(document).on('click', '.view', function() {
                var post_ID = $(this).attr("id");
                $('#post_modal_title').text('View Post');
                $('#post_modal').modal('show');
                $("#submit_input").hide();

                $.ajax({
                    url: "datatable/room_announcement/fetch_single.php",
                    method: 'POST',
                    data: {
                        operation: "post_view",
                        post_ID: post_ID
                    },
                    dataType: 'json',
                    success: function(data) {

                        $("#post_title").prop("disabled", true);
                        $("#post_content").prop("disabled", true);

                        $('#post_title').val(data.post_Name);
                        $('#post_content').val(data.post_Description);


                        $('#submit_input').hide();
                        $('#submit_x').hide();
                        $('#post_comment').show();

                        $('#post_ID').val(post_ID);
                        $('#submit_input').text('View');
                        $('#submit_input').val('post_view');
                        $('#operation').val("post_view");

                        comment_data(post_ID);
                        $('#comment_data').DataTable().destroy();
                    }
                });
            });

            $(document).on('click', '.edit', function() {
                var post_ID = $(this).attr("id");
                $('#post_modal_title').text('Edit Post');
                $('#post_modal').modal('show');
                $("#submit_input").show();
                $('#submit_x').show();
                $('#post_comment').hide();

                $.ajax({
                    url: "datatable/room_announcement/fetch_single.php",
                    method: 'POST',
                    data: {
                        operation: "post_edit",
                        post_ID: post_ID
                    },
                    dataType: 'json',
                    success: function(data) {
                        $("#post_title").prop("disabled", false);
                        $("#post_content").prop("disabled", false);

                        $('#post_title').val(data.post_Name);
                        $('#post_content').val(data.post_Description);

                        $('#submit_input').show();
                        $('#post_ID').val(post_ID);
                        $('#submit_input').text('Update');
                        $('#submit_input').val('post_edit');
                        $('#operation').val("post_edit");
                    }
                });
            });

            $(document).on('click', '.delete', function() {
                var post_ID = $(this).attr("id");
                // $('#delpost_modal').modal('show');
                // $('.submit').hide();
                // $('#post_ID').val(post_ID);
                alertify.confirm('Are you sure you want to delete this post?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/room_announcement/insert.php",
                            data: {
                                operation: "post_delete",
                                post_ID: post_ID
                            },
                            dataType: 'json',
                            complete: function(data) {
                                $('#delpost_modal').modal('hide');
                                alertify.alert(data.responseText).setHeader('Delete Post');
                                dataTable.ajax.reload();
                                dataTable_product_data.ajax.reload();
                            }
                        })
                    },
                    function() {
                        alertify.error('Cancel')
                    }
                ).setHeader('Delete Post');
            });

            // $(document).on('click', '#post_delform', function(event) {
            //     var post_ID = $('#post_ID').val();
            //     $.ajax({
            //         type: 'POST',
            //         url: "datatable/room_announcement/insert.php",
            //         data: {
            //             operation: "post_delete",
            //             post_ID: post_ID
            //         },
            //         dataType: 'json',
            //         complete: function(data) {
            //             $('#delpost_modal').modal('hide');
            //             alertify.alert(data.responseText).setHeader('Delete this Post');
            //             dataTable.ajax.reload();
            //             dataTable_product_data.ajax.reload();
            //         }
            //     })
            // });

            $(document).on('submit', '#comment_form', function(event) {
                event.preventDefault();
                var comment_box = $('#comment_box').val();
                var post_ID = $('#post_ID').val();
                $.ajax({
                    url: "datatable/room_announcement/insert.php",
                    method: 'POST',
                    data: {
                        operation: "post_comment",
                        comment: comment_box,
                        post_ID: post_ID
                    },
                    dataType: 'json',
                    complete: function(data) {
                        $('#comment_box').val('');
                        comment_data(post_ID);
                        $('#comment_data').DataTable().destroy();
                    }
                });
            });

            $(document).on('click', '.delete_comment', function(event) {
                var comment_ID= $(this).attr("id");
                var post_ID = $('#post_ID').val();
                $.ajax({
                    type: 'POST',
                    url: "datatable/room_announcement/insert.php",
                    data: {
                        operation: "delete_comment",
                        comment_ID: comment_ID
                    },
                    dataType: 'json',
                    complete: function(data) {
                        // alertify.success(data.responseText);
                        comment_data(post_ID)
                        $('#comment_data').DataTable().destroy();
                    }
                })
            });
        });
    </script>
</body>

</html>