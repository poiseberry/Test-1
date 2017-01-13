<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">MENU</li>
            <li><a href="#">Dashboard</a></li>
            <li><a href="banner/listing.php">Banner</a></li>
            <li class="treeview"><a href="#"><span>Project</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu" style="display: none;">
                    <li><a href="category/listing.php"><i class="fa fa-circle-o"></i> Category</a></li>
                    <li><a href="timeline/listing.php"><i class="fa fa-circle-o"></i> Timeline</a></li>
                    <li><a href="gallery/listing.php"><i class="fa fa-circle-o"></i>  Gallery</a>
                </ul>
            </li>
            <li><a href="news/listing.php">News</a></li>
            <li><a href="media/edit.php">Gallery</a></li>
            <li class="treeview"><a href="#"><span>Pages</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu" style="display: none;">
                    <li><a href="aboutus/edit.php?id=1"><i class="fa fa-circle-o"></i> About Us</a></li>
                    <li><a href="company/edit.php?id=1"><i class="fa fa-circle-o"></i> Our Company</a></li>
                    <li><a href="profile/edit.php?id=1"><i class="fa fa-circle-o"></i> Our Profile</a></li>
                </ul>
            </li>
            <li><a href="contact/listing.php">Enquiry</a></li>
            <li class="treeview"><a href="#"><span>Users</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu" style="display: none;">
                    <? if ($_SESSION['user_role'] == "1") { ?>
                        <li><a href="user/listing.php"><i class="fa fa-circle-o"></i> Listing</a></li>
                    <? } ?>
                    <li><a href="user/change_password.php"><i class="fa fa-circle-o"></i> Change Password</a></li>
                    <? if ($_SESSION['user_role'] == "1") { ?>
                        <li><a href="access_logs/listing.php"><i class="fa fa-circle-o"></i> Access Logs</a></li>
                        <li><a href="tracking/listing.php"><i class="fa fa-circle-o"></i> Action Tracking</a></li>
                    <? } ?>
                </ul>
            </li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>