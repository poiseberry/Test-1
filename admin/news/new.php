<?php
require '../config.php';
require '../include/image.php';
global $table;
$database = new database();
//get the base name directory of the url
$this_folder = basename(__DIR__);
//if statement when submit button is pressed
if (isset($_POST['submit_save']) && $_POST['submit_save'] == "true") {
    //make a postfield variable to include all the posted data
    $postfield=$_POST;
    //unset the submit button because it is also sent as a posted data
    unset($postfield['submit_save']);
    //if statement when a file name is true
    if ($_FILES['file']['name']) {
        $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $image = uniqid() . "." . $extension;
        move_uploaded_file($_FILES['file']["tmp_name"], "../../files/news/" . $image);
        ImageResize('../../files/news/' . $image, '../../files/news/' . $image, 720, 540);
        $postfield['img_url']=$image;
    }
    //include post that aren't included in the form that is posted
    $postfield['created_date']=date('Y-m-d H:i:s');
    $postfield['created_by']=$user_username;
    //query out the insert of the table which includes the table name,pkid and the postfield data
    $query=get_query_insert($table['news'],$postfield);
    $database->query($query);
    //tracking what the user is doing
    do_tracking($user_username, 'Add New News');
    //after completing head back to the listing page
    header("Location:listing.php?type=new&return=success");
    //exit everything
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
        <form class="form-horizontal" action="<?=$_SERVER['PHP_SELF']."?".http_build_query($_GET)?>" method="post" enctype="multipart/form-data">

            <section class="content-header">
                <h1>
                    News > New
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
                                    <label class="col-sm-2 control-label">Upload<br><small>720x540</small></label>

                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" name="file" id="file" required>
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
                                    <label class="col-sm-2 control-label">Date</label>

                                    <div class="col-sm-10">
                                        <input type="text" class="form-control date" name="date" id="date" required>
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
                            </div>
                        </div>
                        <?= get_button($this_folder, 'save', null) . " " . get_button($this_folder, 'cancel', null) ?>
                    </div>
                </div>
            </section>
        </form>
    </div>
</div>
<? include('../js.php') ?>
<script>
$(document).ready(function(){

    $("#file").fileinput({
        showRemove:false,
        showUpload:false,
        showCancel:false,
        maxFileCount:1,
        maxFileSize:25000,
    });
});
</script>
</body>
</html>

