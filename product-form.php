<?php include("inc_connect.php");?>
<?php if($_SESSION["LOGIN_OK"]!=true || $_SESSION["LOGIN_LEVEL"]<=0)  { header("location: logout.php"); } ?>
<?php

	if($_POST["code"]!="" && $_POST["name"]!="" ) {
		
		$SQL = "select * from tb_product where code='".$_POST["code"]."' and pro_id<>'".$_POST["pro_id"]."'";
		$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
		if(mysqli_num_rows($result)>0) {
			alert("รหัสสินค้าซ้ำ โปรดลองใหม่อีกครั้ง","");
		}
		
		if($_FILES["file"]["tmp_name"]!="") {
			
			@unlink("images-upload/".$_POST["picture"]);

			$filename = $_FILES["file"]["name"];
			$arr = explode(".",$filename);
			$picture = date("YmdHis").".".end($arr);

			move_uploaded_file($_FILES["file"]["tmp_name"], "images-upload/".$picture);
			
		} else {
			$picture = $_POST["picture"];
		}
		
		if($_POST["pro_id"]==0) {
			$SQL = "insert into tb_product (
								code,
								name,
								detail,
								picture,
								qty,
								cost,
								price,
								confirm,
								lastupdate
							) values ( 
								'".$_POST["code"]."',
								'".$_POST["name"]."',
								'".$_POST["detail"]."',
								'".$picture."',
								'".$_POST["qty"]."',
								'".$_POST["cost"]."',
								'".$_POST["price"]."',
								1,
								'".date("Y-m-d H:i:s")."'
							)";

		} else {
			$SQL = "update  tb_product set 
							code='".$_POST["code"]."',
							name='".$_POST["name"]."',
							detail='".$_POST["detail"]."',
							picture='".$picture."',
							qty='".$_POST["qty"]."',
							cost='".$_POST["cost"]."',
							price='".$_POST["price"]."'
						where pro_id='".$_POST["pro_id"]."'";
		}

		mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
		header("location:product.php");
//		alert("บันทึกข้อมูลเสร็จเรียบร้อยแล้ว","product.php");

	} elseif($_GET["del"]!="" && $_GET["pro_id"]!="" && $_GET["picture"]!="") {

		$SQL = "update tb_product set picture='' where pro_id='".$_GET["pro_id"]."' ";
		mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);

		@unlink("images-upload/".$_GET["picture"]);
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

		<h4><span class="label label-primary"><i class="glyphicon glyphicon-barcode"></i> ข้อมูลสินค้า</span></h4>
		<hr>

		<form class="form-horizontal" name="l" id="l" action="?" method="post" enctype="multipart/form-data" onsubmit="return validate();">
		<?php
			$SQL = "select * from tb_product where pro_id='".$_GET["pro_id"]."'";
			$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
			$tmp = mysqli_fetch_array($result);
		?>
		  <div class="form-group">
			<label class="col-sm-2 control-label">รหัสสินค้า</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="text" class="form-control" id="code" name="code" placeholder="ต้องป้อน" maxlength="20" value="<?php print $tmp["code"]?>">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">ชื่อสินค้า</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="text" class="form-control" id="name" name="name" placeholder="ต้องป้อน" maxlength="150" value="<?php print $tmp["name"]?>">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">รายละเอียดสินค้า</label>
			<div class="col-sm-4 col-lg-3">
			  <textarea class="form-control" id="detail" name="detail" placeholder="" rows="5"><?php print $tmp["detail"]?></textarea>
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">รูปภาพ</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="file" class="form-control" id="file" name="file">
			  <?php if($tmp["picture"]!="") { ?>
			  <br/>
			  <a href="images-upload/<?php print $tmp["picture"]?>" target="_blink" class="thumbnail"><img src="images-upload/<?php print $tmp["picture"]?>"></a> <a href="?pro_id=<?php print $tmp["pro_id"]?>&del=true&picture=<?php print $tmp["picture"]?>" onclick="return del();"><i class="glyphicon glyphicon-trash"></i> ลบ</a>
			  <input type="hidden" name="picture" value="<?php print $tmp["picture"]?>">
			  <?php } ?>
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">ราคาต้นทุน</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="number" class="form-control" id="cost" name="cost" placeholder="ต้องป้อน" maxlength="50" value="<?php print $tmp["cost"]?>" min="0" max="100000000">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">ราคาขาย</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="number" class="form-control" id="price" name="price" placeholder="ต้องป้อน" maxlength="50" value="<?php print $tmp["price"]?>" min="0" max="100000000">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">จำนวนคงเหลือ</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="number" class="form-control" id="qty" name="qty" placeholder="" maxlength="50" value="<?php print $tmp["qty"]?>"  min="0" max="100000000" step="1">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label"></label>
			<div class="col-sm-4 col-lg-3">
			  <button type="submit" class="btn btn-primary">บันทึก</button>
			  <button type="reset" class="btn btn-default">ยกเลิก</button>
			  <button type="button" class="btn btn-default" onclick="location.href='product.php';">ย้อนกลับ</button>
			  <input type="hidden" name="pro_id" value="<?php print $_GET["pro_id"]?>">
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
			if($('#code').val()=="") {
				alert("โปรดป้อนรหัสสินค้า");
				$('#code').focus();
				return false;
			}
			if($('#name').val()=="") {
				alert("โปรดป้อนชื่อสินค้า");
				$('#name').focus();
				return false;
			}
			if($('#cost').val()=="") {
				alert("โปรดป้อนราคาต้นทุน");
				$('#cost').focus();
				return false;
			}
			if($('#price').val()=="") {
				alert("โปรดป้อนราคาขาย");
				$('#price').focus();
				return false;
			}
			if($('#qty').val()=="") {
				alert("โปรดป้อนจำนวนคงเหลือ");
				$('#qty').focus();
				return false;
			}

			if(confirm("คุณต้องการบันทึกข้อมูลหรือไม่?")) {
				return true;
			} else {
				return false;
			}
		}
		
		function del() {
			if(confirm("คุณต้องการลบรูปภาพนี้หรือไม่?")) {
				return true;
			} else {
				return false;
			}
		}

	</script>

  </body>
</html>
