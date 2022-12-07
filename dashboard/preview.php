<?php
include('../session.php');

require_once("../class-user.php");

$auth_user = new USER();
// $page_level = 3;
// $auth_user->check_accesslevel($page_level);
$pageTitle = "Manage Account";

if (isset($_REQUEST["attachment"])) {
    $attachment_ID = $_REQUEST["attachment"];
}

$statement = $auth_user->runQuery("SELECT * FROM `attachment` WHERE attachment_id = '$attachment_ID' LIMIT 1");
$statement->execute();
$result = $statement->fetchAll();

foreach ($result as $row) {
    $attachment_Name = json_decode($row["attachment_name"]);

    $attachment_MIME = $row["attachment_mime"];
    $pageTitle = $attachment_Name[0];
    $attachment_Data = base64_encode($row["attachment_data"]);
}


if (isset($attachment_MIME)) {
    if (
        $attachment_MIME == "application/pdf" ||
        $attachment_MIME == "image/jpeg" ||
        $attachment_MIME == "image/gif" ||
        $attachment_MIME == "image/png"
    ) {
?>

        <body style="padding:0px;">
            <title>Preview:<?php echo $pageTitle ?></title>
            <iframe src="data:<?php echo $attachment_MIME ?>;base64,<?php echo $attachment_Data ?>" type="<?php echo $attachment_MIME ?>" width="100%" height="100%" style="overflow: auto; " frameborder="0">
            </iframe>

        </body>
<?php

    } else {
        echo "<h1>View Not Supported For This File Type</h1>";
    }
} else {
    echo "<h1>No Data Available</h1>";
}
?>