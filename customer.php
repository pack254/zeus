<?php include("inc_connect.php");?>
<?php if($_SESSION["LOGIN_OK"]!=true) { alert("คุณไม่มีสิทธิในการใช้งานเมนูนี้","main.php"); } ?>
<?php
	if($_GET["cus_id"]!="" && $_GET["action"]=="del") {
		$SQL = "delete from tb_customer where cus_id='".$_GET["cus_id"]."' ";
		mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
	
	} elseif($_GET["cus_id"]!="" && $_GET["action"]!="") {
		$SQL = "update tb_customer set confirm='".(($_GET["action"]==1)?"0":"1")."' where cus_id='".$_GET["cus_id"]."' ";
#print $SQL;
		mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);

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
	<meta name="keywords" content="ระบบซื้อขายสินค้า">

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

    <link href="vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <link href="vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

	<link href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
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
	

		<div class="col-xs-12">
			<h4><span class="label label-primary"><i class="glyphicon glyphicon-user"></i> ข้อมูลลูกค้า</span>
			<a href="customer-form.php?cus_id=0" class="btn btn-success btn-xs" role="button"><i class="glyphicon glyphicon-star"></i> สร้างลูกค้าใหม่</a>

			</h4>
			<table class="table table-bordered" id="data-tb">
				<thead>
				<tr align="center">
					<td>#</td>
					<td align="left"><strong>ชื่อ-นามสกุล</strong></td>
					<td align="left"><strong>ที่อยู่</strong></td>
					<td align="left"><strong>เบอร์โทรศัพท์</strong></td>
					<td align="left"><strong>อีเมล</strong></td>
					<td><strong>เมนู</strong></td>
				</tr>
				</thead>
				<tbody>
		<?php 
			
			$SQL = "select * from tb_customer order by name ";
			$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
			$i = 0;
			while($tmp = mysqli_fetch_array($result)) {
				$i++;
		?>
				<tr align="center">
					<td><?php print $i?></td>
					<td align="left"><?php print $tmp["name"]?></td>
					<td align="left"><?php print $tmp["address"]?></td>
					<td align="left"><?php print $tmp["phone"]?></td>
					<td align="left"><?php print $tmp["email"]?></td>
					<td nowrap>
					<a href="customer-form.php?cus_id=<?php print $tmp["cus_id"]?>" class="btn btn-default btn-xs" role="button"><i class="glyphicon glyphicon-pencil"></i></a>
					<a href="?cus_id=<?php print $tmp["cus_id"]?>&action=del" class="btn btn-warning btn-xs" onclick="return del();" role="button"><i class="glyphicon glyphicon-trash"></i></a>
					</td>
				</tr>
		<?php 	} ?>
				</tbody>
				<tfoot>
				<tr align="center">
					<td>#</td>
					<td>ชื่อ-นามสกุล</td>
					<td>ที่อยู่</td>
					<td>เบอร์โทรศัพท์</td>
					<td>อีเมล</td>
					<td>เมนู</td>
				</tr>
				</tfoot>
			</table>

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


    <!-- DataTables JavaScript -->
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="vendor/datatables-responsive/dataTables.responsive.js"></script>

	<script>

		$(document).ready(function() {
			var table = $('#data-tb').DataTable(
				{
					width : "100%",
					autoWidth : true,

					  "dom": '<"pull-left"f><"pull-right"l>tip'
				});
	   });

		$('#data-tb').on('search.dt', function() {
			var value = $('.dataTables_filter input').val();
		}); 


		function del() {
			if(confirm("คุณต้องการลบรายการนี้หรือไม่?")) {
				return true;
			} else {
				return false;
			}
		}

		function active(s) {
			if(s==1) {
				if(confirm("คุณต้องการระงับการใช้งานหรือไม่?")) {
					return true;
				} else {
					return false;
				}
			} else {
				if(confirm("คุณต้องการให้ใช้งานหรือไม่?")) {
					return true;
				} else {
					return false;
				}
			}
		}

	</script>

  </body>
</html>
