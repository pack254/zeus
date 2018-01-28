<?php include("inc_connect.php");?>
<?php if($_SESSION["LOGIN_OK"]!=true || $_SESSION["LOGIN_LEVEL"]<=0)  { header("location: logout.php"); } ?>
<?php

	if($_GET["sale_id"]>0) {
		$_SESSION["CART_NO"] = 0;
		$SQL = "select d.*, p.name,p.picture from tb_sale_detail as d, tb_product as p where d.sale_id='".$_GET["sale_id"]."' and d.code=p.code";
		$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
		$i = 0;
		while($tmp = mysqli_fetch_array($result)) {
			$i++;
			$_SESSION["CART_CODE".$i] = $tmp["code"];
			$_SESSION["CART_NAME".$i] = $tmp["name"];
			$_SESSION["CART_PICTURE".$i] = $tmp["picture"];
			$_SESSION["CART_QTY".$i] = $tmp["qty"];
			$_SESSION["CART_PRICE".$i] = $tmp["price"];
			$_SESSION["CART_TOTAL".$i] = $tmp["total"];
			$_SESSION["CART_COST".$i] = $tmp["cost"];
			$_SESSION["CART_COST_TOTAL".$i] = $tmp["cost_total"];

			$_SESSION["CART_NO"]++;
		}
		$SQL = "select * from tb_sale where sale_id='".$_GET["sale_id"]."'";
		$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
		$tmp = mysqli_fetch_array($result);
		$_SESSION["CART_DELIVERY"] = $tmp["delivery"];
		$_SESSION["CART_DISCOUNT"] = $tmp["discount"];
		$_SESSION["CART_GRAND"] = $tmp["grand"];
		session_write_close();


	} elseif($_POST["sale_id"]!="" ) {
		
		$cus_id = 0;
		if($_POST["cus_id"]==0 && $_POST["name"]!="") {
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
			mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
			$cus_id = mysqli_insert_id($mysqli);

		} elseif($_POST["cus_id"]>0) {

			$SQL = "update  tb_customer set 
							name='".$_POST["name"]."',
							address='".$_POST["address"]."',
							phone='".$_POST["phone"]."',
							email='".$_POST["email"]."'
						where cus_id='".$_POST["cus_id"]."'";
			mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
			$cus_id = $_POST["cus_id"];
		}
		
		if($_POST["sale_id"]==0 && $_POST["grand"]>0) {
			$SQL = "insert into tb_sale (
								cus_id,
								total,
								delivery,
								discount,
								grand,
								cost,
								note,
								user_id,
								lastupdate
							) values ( 
								'".$cus_id."',
								'".str_replace(",","",$_POST["total"])."',
								'".str_replace(",","",$_POST["delivery"])."',
								'".str_replace(",","",$_POST["discount"])."',
								'".str_replace(",","",$_POST["grand"])."',
								'".str_replace(",","",$_POST["cost"])."',
								'".$_POST["note"]."',
								'".$_SESSION["LOGIN_ID"]."',
								'".date("Y-m-d H:i:s")."'
							)";
			mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
			$sale_id = mysqli_insert_id($mysqli);

		} elseif($_POST["grand"]>0) {
			$SQL = "update  tb_sale set 
							cus_id='".$cus_id."',
							total='".str_replace(",","",$_POST["total"])."',
							delivery='".str_replace(",","",$_POST["delivery"])."',
							discount='".str_replace(",","",$_POST["discount"])."',
							grand='".str_replace(",","",$_POST["grand"])."',
							cost='".str_replace(",","",$_POST["cost"])."',
							note='".$_POST["note"]."'
						where sale_id='".$_POST["sale_id"]."'";

			mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
			$sale_id = $_POST["sale_id"];
		}

		$SQL = "delete from tb_sale_detail where sale_id='".$sale_id."'";
		mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);

		for($i=1;$i<=$_SESSION["CART_NO"];$i++) {
			$SQL = "insert into tb_sale_detail (
								sale_id,
								code,
								cost,
								price,
								qty,
								total,
								cost_total
								) values(
								'".$sale_id."',
								'".$_SESSION["CART_CODE".$i]."',
								'".$_SESSION["CART_COST".$i]."',
								'".$_SESSION["CART_PRICE".$i]."',
								'".$_SESSION["CART_QTY".$i]."',
								'".$_SESSION["CART_TOTAL".$i]."',
								'".$_SESSION["CART_COST_TOTAL".$i]."'
								)";
			mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);

			$SQL = "update tb_product set qty=qty-".$_SESSION["CART_QTY".$i]." where code='".$_SESSION["CART_CODE".$i]."'";
			mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
		}

		$_SESSION["CART_NO"] = 0;
		$_SESSION["CART_DELIVERY"] = 0;
		$_SESSION["CART_DISCOUNT"] = 0;
		$_SESSION["CART_GRAND"] = 0;
		
		session_write_close();
		header("location: sale.php");
	} else {

		$_SESSION["CART_NO"] = 0;
		$_SESSION["CART_DELIVERY"] = 0;
		$_SESSION["CART_DISCOUNT"] = 0;
		$_SESSION["CART_GRAND"] = 0;
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

    <link href="vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <link href="vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

	<link href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">

 </head>

  <body>
	
	<?php include("inc_menu.php"); ?>

    <div class="container">
	
	<?php include("inc_user.php"); ?>
		<div class="col-xs-12" style="background-color: #ffffff">

		<h4><span class="label label-primary"><i class="glyphicon glyphicon-barcode"></i> ขายสินค้า</span></h4>
		<hr>

		<form class="form-horizontal" name="l" id="l" action="?" method="post" enctype="multipart/form-data" onsubmit="return validate();">
		<?php
			$SQL = "select * from tb_sale where sale_id='".$_GET["sale_id"]."'";
			$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
			$tmp = mysqli_fetch_array($result);
			
			$SQL = "select * from tb_customer where cus_id='".$tmp["cus_id"]."'";
			$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
			$tmp1 = mysqli_fetch_array($result);

		?>
		<h4>ข้อมูลลูกค้า</h4>
		  <div class="form-group">
			<label class="col-sm-2 control-label">ชื่อ-นามสกุล</label>
			<div class="col-sm-4 col-lg-3 form-inline">
			  <input type="text" class="form-control" id="name" name="name" placeholder="" maxlength="150" value="<?php print $tmp1["name"]?>"><button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal" onclick="get_customer();"><i class="glyphicon glyphicon-search"></i></button>
			  <input type="hidden" name="cus_id" value="<?php print ($tmp1["cus_id"]!="")?$tmp1["cus_id"]:0?>">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">ที่อยู่</label>
			<div class="col-sm-7 col-lg-6">
			  <input type="text" class="form-control" id="address" name="address" placeholder="" maxlength="250" value="<?php print $tmp1["address"]?>">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">เบอร์โทรศัพท์</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="text" class="form-control" id="phone" name="phone" placeholder="" maxlength="50" value="<?php print $tmp1["phone"]?>">
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">อีเมล์</label>
			<div class="col-sm-4 col-lg-3">
			  <input type="text" class="form-control" id="email" name="email" placeholder="" maxlength="50" value="<?php print $tmp1["email"]?>">
			</div>
		  </div>

		<h4>รายการสินค้า
		<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal" onclick="get_product();"><i class="glyphicon glyphicon-search"></i></button></h4>
		<div class="form-group">
			<font id="cart"></font>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label">หมายเหตุ</label>
			<div class="col-sm-6 col-lg-5">
			  <textarea name="note" id="note" rows="5" class="form-control" ><?php print $tmp["note"]?></textarea>
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-2 control-label"></label>
			<div class="col-sm-4 col-lg-3">
			  <button type="submit" class="btn btn-primary">บันทึก</button>
			  <button type="reset" class="btn btn-default">ยกเลิก</button>
			  <button type="button" class="btn btn-default" onclick="location.href='sale.php';">ย้อนกลับ</button>
			  <input type="hidden" name="sale_id" value="<?php print $_GET["sale_id"]?>">
			</div>
		  </div>
		</form>
	</div>
	<?php include("inc_footer.php"); ?>

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">ข้อมูล</h4>
		  </div>
		  <div class="modal-body">
			<font id="temp"><table class="table table-bordered" id="data-tb"></table></font>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  </div>
		</div>
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
    <script src="dist/js/bootstrap-modal.js"></script>
    <script src="dist/js/modal.js"></script>

    <!-- DataTables JavaScript -->
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="vendor/datatables-responsive/dataTables.responsive.js"></script>

	<script>


		$('#data-tb').on('search.dt', function() {
			var value = $('.dataTables_filter input').val();
		}); 


		function get_customer() {
			$.ajax({
				method: "POST",
				url: "ajax.php",
				data: { cond: 'customer' }
			})
			.done(function( msg ) {
				$('#temp').html(msg);

			var table = $('#data-tb').DataTable(
				{
					width : "100%",
					autoWidth : true,
					  "dom": '<"pull-left"f><"pull-right"l>tip'
				});

			});
		}

		function load_customer(cus_id, name, address, phone, email) {
			$('#cus_id').val(cus_id);
			$('#name').val(name);
			$('#address').val(address);
			$('#phone').val(phone);
			$('#email').val(email);
			$('#temp').html('');
			$('#myModal').modal('hide');
		}

		function load_cart() {
			$.ajax({
				method: "POST",
				url: "ajax.php",
				data: { cond: 'load_cart' , val: <?php print $_GET["sale_id"]?>}
			})
			.done(function( msg ) {
				$('#cart').html(msg);
			});
		}

		function get_product() {
			$.ajax({
				method: "POST",
				url: "ajax.php",
				data: { cond: 'product' }
			})
			.done(function( msg ) {
				$('#temp').html(msg);

			var table = $('#data-tb').DataTable(
				{
					width : "100%",
					autoWidth : true,
					  "dom": '<"pull-left"f><"pull-right"l>tip'
				});

			});
		}
		
		function load_product(code, close) {
			$.ajax({
				method: "POST",
				url: "ajax.php",
				data: { cond: 'add_to_cart' , val: code }
			})
			.done(function( msg ) {
				load_cart();
				if(close=='close') {
					$('#temp').html('');
					$('#myModal').modal('hide');
				}
			});
		}

		function recal() {
			$.ajax({
				method: "POST",
				url: "ajax.php",
				data: { cond: 'recal' , delivery: $('#delivery').val() , discount: $('#discount').val() }
			})
			.done(function( msg ) {
				load_cart();
			});
		}

		function recal1(no, qty) {
			$.ajax({
				method: "POST",
				url: "ajax.php",
				data: { cond: 'recal1' , no: no , qty: qty }
			})
			.done(function( msg ) {
				load_cart();
			});
		}
	
		function remove_product(no) {
			$.ajax({
				method: "POST",
				url: "ajax.php",
				data: { cond: 'remove_product' , no: no }
			})
			.done(function( msg ) {
				load_cart();
			});
		}

		function validate() {
/*
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
*/
			if(confirm("คุณต้องการบันทึกข้อมูลหรือไม่?")) {
				return true;
			} else {
				return false;
			}
		}
		
		
		load_cart();

	</script>

  </body>
</html>
