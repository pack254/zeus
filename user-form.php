<?php include("inc_connect.php");?>
<?php if($_SESSION["LOGIN_OK"]!=true || $_SESSION["LOGIN_LEVEL"]<=0)  { header("location: logout.php"); } ?>
<?php

	if($_POST["username"]!="" && $_POST["name"]!="" ) {
		
		$SQL = "select * from tb_user where username='".$_POST["username"]."' and user_id<>'".$_POST["user_id"]."'";
		$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
		if(mysqli_num_rows($result)>0) {
			alert("ซื้อผู้ใช้งานซ้ำ โปรดลองใหม่อีกครั้ง","");
		}
		
		if($_POST["user_id"]==0) {
			$SQL = "insert into tb_user (
								username,
								password,
								name,
								idcard,
								address,
								phone,
								email,
								level,
								confirm,
								lastupdate
							) values ( 
								'".$_POST["username"]."',
								'".md5($_POST["password"])."',
								'".$_POST["name"]."',
								'".$_POST["idcard"]."',
								'".$_POST["address"]."',
								'".$_POST["phone"]."',
								'".$_POST["email"]."',
								'".$_POST["level"]."',
								1,
								'".date("Y-m-d H:i:s")."'
							)";

		} else {
			$password = ($_POST["password"]!="")?"password='".md5($_POST["password"])."',":"";
			$SQL = "update  tb_user set 
							username='".$_POST["username"]."',
							name='".$_POST["name"]."',
							idcard='".$_POST["idcard"]."',
							address='".$_POST["address"]."',
							phone='".$_POST["phone"]."',
							level='".$_POST["level"]."',
							".$password."
							email='".$_POST["email"]."'
						where user_id='".$_POST["user_id"]."'";
		}

		mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
//		alert("บันทึกข้อมูลเสร็จเรียบร้อยแล้ว","user.php");
		header("location:user.php");

	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="ระบบซื้อขายสินค้า">
    <meta name="author" content="zues.com">
	<meta name="keywords" content="ระบบซื้อขายสินค้า ">

    <title>ระบบซื้อขายสินค้า</title>
	<link rel="shortcut icon" href="images/zues.jpg">

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dist/css/offcanvas.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


 </head>

  <body>
	
	<?php include("inc_menu.php"); ?>

    <div class="container">
	
	<?php include("inc_user.php"); ?>
		<div class="col-xs-12" style="background-color: #ffffff">

		<h4><span class="label label-primary"><i class="glyphicon glyphicon-user"></i> ข้อมูลผู้ใช้งาน</span></h4>
		<hr>

		<form class="form-horizontal" name="l" id="l" action="?" method="post" onsubmit="return validate();">
		<?php
			$SQL = "select * from tb_user where user_id='".$_GET["user_id"]."'";
			$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
			$tmp = mysqli_fetch_array($result);
		?>
		  <div class="form-group">
			<label class="col-sm-2 control-label">ชื่อผู้ใช้งาน</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="username" class="form-control" id="username" name="username" placeholder="ต้องป้อน" maxlength="50" value="<?php print $tmp["username"]?>">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">รหัสผ่าน</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="password" class="form-control" id="password" name="password" placeholder="<?php print ($_GET["user_id"]==0)?"ต้องป้อน":"ถ้าต้องการเปลี่ยนรหัสผ่านให้ป้อน"?>" maxlength="50" value="">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">ชื่อ-นามสกุล</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="text" class="form-control" id="name" name="name" placeholder="ต้องป้อน" maxlength="100" value="<?php print $tmp["name"]?>">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">เลขบัตรประชาชน</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="number" class="form-control" id="idcard" name="idcard" placeholder="ต้องป้อน" maxlength="13" value="<?php print $tmp["idcard"]?>">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">ที่อยู่</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="text" class="form-control" id="address" name="address" placeholder="ต้องป้อน" maxlength="200" value="<?php print $tmp["address"]?>">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">เบอร์โทรติดต่อ</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="text" class="form-control" id="phone" name="phone" placeholder="ต้องป้อน" maxlength="50" value="<?php print $tmp["phone"]?>">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">อีเมล</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="text" class="form-control" id="email" name="email" placeholder="" maxlength="50" value="<?php print $tmp["email"]?>">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">ระดับการใช้งาน</label>
			<div class="col-sm-10 col-lg-10">
				<label class="radio-inline">
				  <input type="radio" name="level" id="level" value="2" <?php print ($tmp["level"]==2 || $_GET["user_id"]==0)?"checked":""?>> Sale
				</label>			  
				<label class="radio-inline">
				  <input type="radio" name="level" id="level" value="1" <?php print ($tmp["level"]==1)?"checked":""?>> Administrator
				</label>			  
			</div>
		  </div>


		  <div class="form-group">
			<label class="col-sm-2 control-label"></label>
			<div class="col-sm-4 col-lg-3">
			  <button type="submit" class="btn btn-primary">บันทึก</button>
			  <button type="reset" class="btn btn-default">ยกเลิก</button>
			  <button type="button" class="btn btn-default" onclick="location.href='user.php';">ย้อนกลับ</button>
			  <input type="hidden" name="user_id" value="<?php print $_GET["user_id"]?>">
			</div>
		  </div>
		</form>
	</div>
	<?php include("inc_footer.php"); ?>

	</div><!--/.container-->

	<?php include("inc_footerpage.php"); ?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>

	<script>
		function validate() {
			if($('#username').val()=="") {
				alert("โปรดป้อนชื่อผู้ใช้งาน");
				$('#username').focus();
				return false;
			}
<?php if($_GET["user_id"]==0) { ?>
			if($('#password').val()=="") {
				alert("โปรดป้อนรหัสผ่าน");
				$('#password').focus();
				return false;
			}
<?php } ?>
			if($('#name').val()=="") {
				alert("โปรดป้อนชื่อผู้ใช้งาน");
				$('#name').focus();
				return false;
			}
			if($('#idcard').val()=="") {
				alert("โปรดป้อนเลขบัตรประชาชน");
				$('#idcard').focus();
				return false;
			}
			if($('#idcard').val().length!=13) {
				alert("โปรดป้อนเลขบัตรประชาชนให้ครบ 13 ตัว");
				$('#idcard').focus();
				return false;
			}
			if($('#address').val()=="") {
				alert("โปรดป้อนที่อยู่");
				$('#address').focus();
				return false;
			}
			if($('#phone').val()=="") {
				alert("โปรดป้อนเบอร์โทรศัพท์");
				$('#phone').focus();
				return false;
			}

			if(confirm("คุณต้องการบันทึกข้อมูลหรือไม่?")) {
				return true;
			} else {
				return false;
			}
		}
	</script>

  </body>
</html>
