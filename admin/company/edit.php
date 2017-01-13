<?php
require '../config.php';
require '../include/image.php';
global $table;
$database = new database();
$this_folder = basename(__DIR__);

$pkid = mysql_real_escape_string($_GET['id']);

$query = "select * from " . $table['company'] . " where pkid=$pkid";
$result = $database->query($query);
$rs_array = $result->fetchRow();

if (isset($_POST['submit_save']) && $_POST['submit_save'] == "true") {
    $postfield = $_POST;

    unset($postfield['submit_save']);

    $postfield['content']=stripcslashes($_POST['content']);
    $postfield['content_cn']=stripcslashes($_POST['content_cn']);

    $query = get_query_update($table['company'], $pkid, $postfield);
    $database->query($query);

    do_tracking($user_username, 'Edit Our Company');

    header("Location:edit.php?id=$pkid&type=edit&return=success");
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
                    Our Company > Edit
                </h1>
                <br>
                <?= get_button($this_folder, 'save', null) ?>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Content</label>

                                    <div class="col-sm-10">
                                        <textarea class="form-control editor" name="content"><?= $rs_array['content'] ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Content (CN)</label>

                                    <div class="col-sm-10">
                                        <textarea class="form-control editor" name="content_cn"><?= $rs_array['content_cn'] ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content-header">
                <?= get_button($this_folder, 'save', null) ?>
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
        showRemove:false,
        showUpload:false,
        showCancel:false,
        maxFileCount:1,
        maxFileSize:25000,
        <?if($rs_array['img_url']!=""){?>
        initialPreview: [
            "<img src='../files/category/<?=$rs_array['img_url']?>' class='file-preview-image img-responsive'>"
        ],
        <?}?>
    });
</script>
</body>
</html>

