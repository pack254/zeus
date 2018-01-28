<?php include("inc_connect.php");?>
<?php if($_SESSION["LOGIN_OK"]!=true)  { header("location: logout.php"); } ?>
<?php

	if($_POST["name"]!="" ) {
				
		if($_POST["cus_id"]==0) {
			$SQL = "insert into tb_customer (
								name,
								address,
								phone,
								email,
								lastupdate
							) values ( 
								'".$_POST["name"]."',
								'".$_POST["address"]."',
								'".$_POST["phone"]."',
								'".$_POST["email"]."',
								'".date("Y-m-d H:i:s")."'
							)";

		} else {

			$SQL = "update  tb_customer set 
							name='".$_POST["name"]."',
							address='".$_POST["address"]."',
							phone='".$_POST["phone"]."',
							email='".$_POST["email"]."'
						where cus_id='".$_POST["cus_id"]."'";
		}

		mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
//		alert("บันทึกข้อมูลเสร็จเรียบร้อยแล้ว","user.php");
		header("location:customer.php");

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
	<link rel="shortcut icon" href="<?php print $elink?>images/zues.jpg">

    <!-- Bootstrap core CSS -->
    <link href="<?php print $elink?>dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?php print $elink?>assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php print $elink?>dist/css/offcanvas.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="<?php print $elink?>assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?php print $elink?>assets/js/ie-emulation-modes-warning.js"></script>

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

		<h4><span class="label label-primary"><i class="glyphicon glyphicon-user"></i> ข้อมูลลูกค้า</span></h4>
		<hr>

		<form class="form-horizontal" name="l" id="l" action="?" method="post" onsubmit="return validate();">
		<?php
			$SQL = "select * from tb_customer where cus_id='".$_GET["cus_id"]."'";
			$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
			$tmp = mysqli_fetch_array($result);
		?>

		  <div class="form-group">
			<label class="col-sm-2 control-label">ชื่อ-นามสกุล</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="text" class="form-control" id="name" name="name" placeholder="ต้องป้อน" maxlength="100" value="<?php print $tmp["name"]?>">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">ที่อยู่</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="text" class="form-control" id="address" name="address" placeholder="" maxlength="250" value="<?php print $tmp["address"]?>">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">เบอร์โทรติดต่อ</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="text" class="form-control" id="phone" name="phone" placeholder="" maxlength="50" value="<?php print $tmp["phone"]?>">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">อีเมล</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="text" class="form-control" id="email" name="email" placeholder="" maxlength="50" value="<?php print $tmp["email"]?>">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label"></label>
			<div class="col-sm-4 col-lg-3">
			  <button type="submit" class="btn btn-primary">บันทึก</button>
			  <button type="reset" class="btn btn-default">ยกเลิก</button>
			  <button type="button" class="btn btn-default" onclick="location.href='customer.php';">ย้อนกลับ</button>
			  <input type="hidden" name="cus_id" value="<?php print $_GET["cus_id"]?>">
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
    <script>window.jQuery || document.write('<script src="<?php print $elink?>assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="<?php print $elink?>dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php print $elink?>assets/js/ie10-viewport-bug-workaround.js"></script>

	<script>
		function validate() {
			if($('#name').val()=="") {
				alert("โปรดป้อนชื่อผู้ใช้งาน");
				$('#name').focus();
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
