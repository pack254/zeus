<?php include("inc_connect.php");?>
<?php

	if($_COOKIE["LOGIN_ID"]>0 && $_GET["logout"]=="") {
		$_SESSION["LOGIN_OK"] = true;
		$_SESSION["LOGIN_ID"] = $_COOKIE["LOGIN_ID"];
		$_SESSION["LOGIN_NAME"] = $_COOKIE["LOGIN_NAME"];
		$_SESSION["LOGIN_LEVEL"] = $_COOKIE["LOGIN_LEVEL"];
		session_write_close();
		header("location: main.php");

	} else {

		unset($_COOKIE['LOGIN_ID']);
		unset($_COOKIE['LOGIN_LEVEL']);
		setcookie('LOGIN_ID', null, time()-3600);
		setcookie('LOGIN_LEVEL', null, time()-3600);
	}

	if($_POST["username"]!="" && $_POST["password"]!="") {

		$SQL = "select * from tb_user where username='".$_POST["username"]."' ";
		$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
		$tmp = mysqli_fetch_array($result);
		if($tmp["username"]==$_POST["username"] && ($tmp["password"])==md5($_POST["password"]) && $_POST["username"]!="" && $_POST["password"]!="") {
			if($tmp["confirm"]==1) {
			
				if($_POST["remember"]!="") {
					setcookie("LOGIN_ID",$tmp["user_id"], strtotime( '+30 days' ));
					setcookie("LOGIN_OK", true, strtotime( '+30 days' ));
					setcookie("LOGIN_NAME", $tmp["name"], strtotime( '+30 days' ));
					setcookie("LOGIN_LEVEL", $tmp["level"], strtotime( '+30 days' ));
				}
				$_SESSION["LOGIN_OK"] = true;
				$_SESSION["LOGIN_ID"] = $tmp["user_id"];
				$_SESSION["LOGIN_NAME"] = $tmp["name"];
				$_SESSION["LOGIN_LEVEL"] = $tmp["level"];

				session_write_close();
				header("location: main.php");

			} else {
				alert("คุณถูกระงับการใช้งานหรือยังไม่ได้รับการอนุมัติให้ใช้งาน โปรดรอการอนุมัติหรือติดต่อผู้ดูแลระบบ","");
			}

		} else {

			alert("ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง","");
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
    <meta name="author" content="sale.com">
	<meta name="keywords" content="ระบบซื้อขายสินค้า">

    <title>ระบบซื้อขายสินค้า</title>
	<link rel="shortcut icon" href="images/zues.jpg">

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<style>
	.footer {
	  position: absolute;
	  right: 0;
	  bottom: 0;
	  left: 0;
	  text-align: center;
	}
</style>

 </head>

  <body>
    <div class="container">
		<div class="row visible-lg"><br/></div>
		<form class="form-horizontal" name="l" id="l" action="?" method="post" onsubmit="return validate();">

			<div class="form-group">
				<div class="col-xs-12 text-center">
					<img src="images/zues.jpg" width="250" class="img-responsive" style="margin: 0 auto;">
						<h3>ระบบซื้อขายสินค้า</h3>
				</div>
			</div>
		  <div class="form-group ">
			<center>
			  <input type="username" class="form-control" id="username" name="username" placeholder="ชื่อผู้ใช้งาน" value="<?php print $_COOKIE["LOGIN_USERNAME"]?>" style="width: 230px">
			  </center>
		  </div>

		  <div class="form-group">
			<center>
			  <input type="password" class="form-control" id="password" name="password" placeholder="รหัสผ่าน"  style="width: 230px">
			  </center>
		  </div>

		  <div class="form-group">
				<div class="col-xs-12 text-center">
			  <div class="checkbox text-center">
				<label>
				  <input type="checkbox" name="remember" value="true"> จำฉันใว้
				</label>
			  </div>
			</div>
		  </div>

		  <div class="form-group">
				<div class="col-xs-12 text-center">
			  <button type="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
			  <button type="reset" class="btn btn-default">ยกเลิก</button>
			</div>
		  </div>
		</form>

			<div class="form-group">
				<div class="col-xs-12 text-center">
						<h5><img src="images/zues.jpg" width="75"> ZUES 2017</h5>
				</div>
			</div>


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
		document.l.username.focus();
		function validate() {
			if($('#username').val()=="") {
				alert("โปรดป้อนชื่อผู้ใช้งาน");
				$('#username').focus();
				return false;
			}
			if($('#password').val()=="") {
				alert("โปรดป้อนรหัสผ่าน");
				$('#password').focus();
				return false;
			}
		}

	</script>

  </body>
</html>
