<button onclick="topFunction()" id="myBtn" title="Go to top" style="display:none">Top</button>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="main.php">ZUES</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="main.php"><i class="glyphicon glyphicon-home"></i> หน้าแรก</a>
                    </li>
<?php if($_SESSION["LOGIN_LEVEL"]==1) {?>
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-cog"></i> ข้อมูลเริ่มต้น <span class="caret"></span></a>
					  <ul class="dropdown-menu">
						<li><a href="user.php"><i class="glyphicon glyphicon-user"></i> ข้อมูลผู้ใช้งาน</a></li>
						<li><a href="product.php"><i class="glyphicon glyphicon-barcode"></i> ข้อมูลสินค้า</a></li>
					  </ul>
					</li>
<?php } ?>
					<li>
                        <a href="customer.php"><i class="glyphicon glyphicon-user"></i> ข้อมูลลูกค้า</a>
                    </li>
					<li>
                        <a href="sale.php"><i class="glyphicon glyphicon-usd"></i> ระบบซื้อขาย</a>
                    </li>
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-print"></i> รายงาน <span class="caret"></span></a>
					  <ul class="dropdown-menu">
						<li><a href="report1.php"><i class="glyphicon glyphicon-print"></i> รายงานการขาย</a></li>
						<li><a href="report2.php"><i class="glyphicon glyphicon-print"></i> สรุปยอดขาย</a></li>
						<li><a href="report3.php"><i class="glyphicon glyphicon-print"></i> สินค้าขายดี</a></li>
					  </ul>
					</li>
					<li>
                        <a href="profiles.php"><i class="glyphicon glyphicon-heart"></i> ข้อมูลส่วนตัว</a>
                    </li>
                    <li>
                        <a href="password.php"><i class="glyphicon glyphicon-refresh"></i> เปลี่ยนรหัสผ่าน</a>
                    </li>
                    <li>
                        <a href="logout.php" onclick="if(confirm('คุณต้องการออกจากระบบหรือไม่?')){ return true} else { return false}"><i class="glyphicon glyphicon-off"></i> ออกจากระบบ</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
