<?php include("inc_connect.php");?>
<?php if($_SESSION["LOGIN_OK"]!=true || $_SESSION["LOGIN_LEVEL"]<=0)  { header("location: logout.php"); } ?>
<?php

	if($_POST["password"]!="" && $_POST["password1"]!="" && $_POST["password2"]!="" && $_POST["password1"]==$_POST["password2"]) {

		$SQL = "select * from tb_user where user_id='".$_SESSION["LOGIN_ID"]."'";
		$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
		$tmp = mysqli_fetch_array($result);
		if($tmp["password"]==md5($_POST["password"])) {
			
			$SQL = "update tb_user set password='".md5($_POST["password1"])."' where user_id='".$_SESSION["LOGIN_ID"]."'";
			mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
			alert("เปลี่ยนรหัสผ่านเสร็จเรียบร้อยแล้ว โปรดล๊อกอินใหม่อีกครั้ง","logout.php");

		} else {
			alert("คุณป้อนรหัสผ่านเก่าไม่ถูกต้อง","next");
		}
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
		<h4><span class="label label-primary"><i class="glyphicon glyphicon-refresh"></i> เปลี่ยนรหัสผ่าน</span></h4>
		<hr>
		
		<form class="form-horizontal" name="l" id="l" action="?" method="post" onsubmit="return validate();">

		  <div class="form-group">
			<label for="password" class="col-sm-2 col-sm-offset-2 control-label">ยืนยันรหัสผ่านเก่า</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="password" class="form-control" id="password" name="password" placeholder="ต้องป้อน">
			</div>
		  </div>

		  <div class="form-group">
			<label for="password" class="col-sm-2 col-sm-offset-2 control-label">รหัสผ่านใหม่</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="password" class="form-control" id="password1" name="password1" placeholder="ต้องป้อน">
			</div>
		  </div>


		  <div class="form-group">
			<label for="password" class="col-sm-2 col-sm-offset-2 control-label">ยืนยันรหัสผ่านใหม่</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="password" class="form-control" id="password2" name="password2" placeholder="ต้องป้อน">
			</div>
		  </div>


		  <div class="form-group">
			<label for="password" class="col-sm-2 col-sm-offset-2 control-label"></label>
			<div class="col-sm-4 col-lg-3">
			  <button type="submit" class="btn btn-primary">เปลี่ยนรหัสผ่าน</button>
			  <button type="reset" class="btn btn-default">ยกเลิก</button>
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
			if($('#password').val()=="") {
				alert("โปรดป้อนรหัสผ่านเก่า");
				$('#password').focus();
				return false;
			}
			if($('#password1').val()=="") {
				alert("โปรดป้อนรหัสผ่านใหม่");
				$('#password1').focus();
				return false;
			}
			if($('#password2').val()=="") {
				alert("โปรดป้อนยืนยันรหัสผ่านใหม่");
				$('#password2').focus();
				return false;
			}
			if($('#password1').val()!=$('#password2').val()) {
				alert("โปรดป้อนรหัสผ่านใหม่กับยืนยันรหัสผ่านใหม่ให้เหมือนกัน");
				$('#password2').focus();
				return false;
			}
			if(confirm("คุณต้องการเปลี่ยนรหัสผ่านหรือไม่?")) {
				return true;
			} else {
				return false;
			}
		}
	</script>

  </body>
</html>
