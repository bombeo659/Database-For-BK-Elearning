<?php
if (isset($_REQUEST["room_ID"])) {
    $room_ID = $_REQUEST["room_ID"];
}

function roomtablist($req_name, $name, $link, $id)
{
    if ($req_name == $link) {
        $active_ul_rnav = "active";
        $active_ul_rnav_span = '<span class="sr-only">(current)</span>';
    } else {
        $active_ul_rnav = '';
        $active_ul_rnav_span = '';
    }
?>
    <li class="page-item <?php echo $active_ul_rnav; ?>">
        <a class="page-link" href="<?php echo $link ?>?room_ID=<?php echo $id ?>"><?php echo $name . ' ' . $active_ul_rnav_span; ?></a>
    </li>
<?php
}
$rad = $auth_user->room_adviser($room_ID);
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb bcrum bg-dark">
        <li class="breadcrumb-item "><a href="index" class="bcrum_i_a">Dashboard</a></li>
        <?php 
            if($auth_user->admin_level()){
                echo "<li class='breadcrumb-item'>
                    <a href='subject' class='bcrum_i_a'>" . $rad["subject_name"] . " - " . $rad["class_name"] . " </a>
                    </li>";
            } else {
                echo "<li class='breadcrumb-item'>
                    <a href='room' class='bcrum_i_a'>" . $rad["subject_name"] . " - " . $rad["class_name"] . " </a>
                    </li>";
            }
        ?>
        
        <li class="breadcrumb-item active bcrum_i_ac" aria-current="page"><?php echo $rtab_c ?></li>
    </ol>
</nav>
<nav>
    <table class="table table-bordered table-sm">
        <tbody>
            <tr>
                <td colspan="2" class="text-center"><?php echo $rad["class_name"] . ' - ' . $rad["subject_name"] ?></td>
            </tr>
            <tr>
                <td width="10%">Teacher:</td>
                <td><?php echo $rad["fullname"] ?></td>
            </tr>
            <tr>
                <td>School Year:</td>
                <td><?php echo $rad["schoolyear"] ?></td>
            </tr>
        </tbody>
    </table>
    <ul class="pagination pg-dark">
        <?php
        roomtablist($rtab, "Announcement", "room_announcement", $room_ID);
        roomtablist($rtab, "Modules", "room_module", $room_ID);
        roomtablist($rtab, "Students", "room_student", $room_ID);
        roomtablist($rtab, "Activity", "room_activity", $room_ID);
        ?>
    </ul>
</nav>