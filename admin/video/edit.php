<?php
require '../config.php';
require '../include/image.php';
global $table;
$database = new database();
$this_folder = basename(__DIR__);
$pkid = mysql_real_escape_string($_GET['id']);

$query = "select * from " . $table['video'] . " where pkid=$pkid";
$result = $database->query($query);
$rs_array = $result->fetchRow();

if (isset($_POST['submit_save']) && $_POST['submit_save'] == "true") {
    $postfield = $_POST;
    unset($postfield['submit_save']);

    if (preg_match('/embed/', $postfield['video_url'])) {
        $img = str_replace("https://","", $postfield['video_url']);
        $img = str_replace("http://","", $img);
        $img = explode("/", $img);
        $img_url = 'https://img.youtube.com/vi/' . $img[2] . '/default.jpg';

        $postfield['img_url'] = $img_url;
    }elseif (preg_match('/youtube/', $postfield['video_url'])) {
        $img = explode("=", $postfield['video_url']);
        $img_url = 'https://img.youtube.com/vi/' . $img[1] . '/default.jpg';

        $postfield['img_url'] = $img_url;
    } else {
        header("Location:listing.php?type=custom&return=failed&return_message=Invalid Video Link<br>Changes discarded");
        exit();
    }

    $postfield['video_url'] = convert_youtube_url($postfield['video_url']);

    $postfield['status']=mysql_real_escape_string($_POST['status']);
    $postfield['updated_date'] = date('Y-m-d H:i:s');
    $postfield['updated_by'] = $user_username;

    $query = get_query_update($table['video'], $pkid, $postfield);
    $database->query($query);

    do_tracking($user_username, 'Edit Video');

    header("Location:listing.php?type=edit&return=success");
    exit();
}
?>
<!DOCTYPE html>
<html>
<? include('../head.php') ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <? include('../header.php') ?>
    <? include('../left.php') ?>

    <div class="content-wrapper">
        <form class="form-horizontal" action="<?= $_SERVER['PHP_SELF'] . "?" . http_build_query($_GET) ?>" method="post"
              enctype="multipart/form-data">

            <section class="content-header">
                <h1>
                    Video > Edit
                </h1>
                <br>
                <?= get_button($this_folder, 'save', null) . " " . get_button($this_folder, 'cancel', null) ?>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Status</label>

                                    <div class="col-sm-10">
                                        <input type="checkbox" name="status"
                                               value="1" <?= $rs_array['status'] == "1" ? "checked" : "" ?>>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Title</label>

                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="title"
                                               value="<?= $rs_array['title'] ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Video Link (YouTube)</label>

                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="video_url"
                                               value="<?= $rs_array['video_url'] ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Display Order</label>

                                    <div class="col-sm-10">
                                        <input type="text" class="form-control number" name="sort_order"
                                               value="<?= $rs_array['sort_order'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content-header">
                <?= get_button($this_folder, 'save', null) . " " . get_button($this_folder, 'cancel', null) ?>
                <br>
                <br>
                <br>
            </section>
        </form>
    </div>
</div>
<? include('../js.php') ?>
<script>
    $("#file").fileinput({
        showRemove: false,
        showUpload: false,
        showCancel: false,
        maxFileCount: 1,
        maxFileSize: 25000,
        <?if($rs_array['img_url'] != ""){?>
        initialPreview: [
            "<img src='../files/category/<?=$rs_array['img_url']?>' class='file-preview-image img-responsive'>"
        ],
        <?}?>
    });
</script>
</body>
</html>

