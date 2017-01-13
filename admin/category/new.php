<?php
require '../config.php';
require '../include/image.php';
global $table;
$database = new database();
$this_folder = basename(__DIR__);

if (isset($_POST['submit_save']) && $_POST['submit_save'] == "true") {
    $postfield = $_POST;

    unset($postfield['submit_save']);

    $postfield['description']=stripcslashes($_POST['description']);
    $postfield['description_cn']=stripcslashes($_POST['description_cn']);

    if ($_FILES['file']['name']) {
        $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $image = uniqid() . "." . $extension;
        move_uploaded_file($_FILES['file']["tmp_name"], "../../files/category/" . $image);
        ImageResize('../../files/category/' . $image, '../../files/category/' . $image, 1920, 1920);
        $postfield['img_url']=$image;
    }

    $postfield['created_date'] = date('Y-m-d H:i:s');
    $postfield['created_by'] = $user_username;

    $query = get_query_insert($table['category'], $postfield);
    $database->query($query);

    do_tracking($user_username, 'Add New Category');

    header("Location:listing.php?type=new&return=success");
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
                    Category > New
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
                                        <input type="checkbox" name="status" value="1" checked>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Upload</label>

                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" name="file" id="file" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Heading</label>

                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="heading" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Heading (CN)</label>

                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="heading_cn" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Title</label>

                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="title" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Title (CN)</label>

                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="title_cn" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Description</label>

                                    <div class="col-sm-10">
                                        <textarea class="form-control editor" name="description"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Description (CN)</label>

                                    <div class="col-sm-10">
                                        <textarea class="form-control editor" name="description_cn"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Display Order</label>

                                    <div class="col-sm-10">
                                        <input type="text" class="form-control number" name="sort_order">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content-header">
                <?= get_button($this_folder, 'save', null) . " " . get_button($this_folder, 'cancel', null) ?>
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
    });
</script>
</body>
</html>

