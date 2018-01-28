<?php include("inc_connect.php");?>
<?php if($_SESSION["LOGIN_OK"]!=true) { alert("คุณไม่มีสิทธิในการใช้งานเมนูนี้","main.php"); } ?>
<?php

	$_POST["sdate"] = ($_POST["sdate"]!="")?$_POST["sdate"]:date("Y-m-d", mktime(0,0,0,date("m")-1,date("d"),date("Y")) );
	$_POST["edate"] = ($_POST["edate"]!="")?$_POST["edate"]:date("Y-m-d");

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
			<h4><span class="label label-primary"><i class="glyphicon glyphicon-usd"></i> สรุปยอดขาย</span></h4>
			
			<form name="f" action="?" method="post" class="form-inline text-center">
			ระหว่างวันที่ <input type="date" name="sdate" id="sdate" value="<?php print $_POST["sdate"]?>" class="form-control"> 
			ถึงวันที่ <input type="date" name="edate" id="edate" value="<?php print $_POST["edate"]?>" class="form-control">
			<button type="submit" class="btn btn-primary">ค้นหา</button>
			</form>
			<br/>
			<table class="table table-bordered" id="data-tb">
				<thead>
				<tr align="center">
					<td align="center"><strong>วันที่ขาย</strong></td>
					<td align="right"><strong>รวมเงิน</strong></td>
					<td align="right"><strong>ค่าขนส่ง</strong></td>
					<td align="right"><strong>ส่วนลด</strong></td>
					<td align="right"><strong>รวมเงินทั้งสิ้น</strong></td>
					<td align="right"><strong>เมนู</strong></td>
				</tr>
				</thead>
				<tbody>
		<?php 
			
			list($y,$m,$d) = explode("-",$_POST["sdate"]);
			list($y1,$m1,$d1) = explode("-",$_POST["edate"]);

			if(($y.$m.$d) > ($y1.$m1.$d1)) {
				$s = $_POST["sdate"];
				$_POST["sdate"] = $_POST["edate"];
				$_POST["edate"] = $s;
			}
	
			$max = dateDifference($_POST["edate"], $_POST["sdate"], '%a');
#print $max;
			
			$SQL = "select sum(total) as stotal, sum(delivery) as sdelivery, sum(discount) as sdiscount, sum(grand) as sgrand from tb_sale where lastupdate between '".$_POST["sdate"]." 00:00:00' and '".$_POST["edate"]." 23:59:59'  ";
			$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
			$tmp = mysqli_fetch_array($result);
			$grand = $tmp["sgrand"];

			for($i=0;$i<=$max;$i++) {
				$date = date("Y-m-d", mktime(0,0,0,$m,$d+$i,$y));
				$SQL = "select sum(total) as stotal, sum(delivery) as sdelivery, sum(discount) as sdiscount, sum(grand) as sgrand from tb_sale where lastupdate between '".$date." 00:00:00' and '".$date." 23:59:59'  ";
	#print $SQL;
				$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
				$tmp = mysqli_fetch_array($result);
				
				$percent = (($tmp["sgrand"]/$grand)*100);
		?>
				<tr align="center">
					<td align="center"><?php print show_date($date)?>
						<div class="progress">
						  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php print $percent?>%">
							<span class="sr-only"><?php print $percent?>% Complete (success)</span>
						  </div>
						</div>
					</td>
					<td align="right"><?php print number_format($tmp["stotal"],2)?></td>
					<td align="right"><?php print number_format($tmp["sdelivery"],2)?></td>
					<td align="right"><?php print number_format($tmp["sdiscount"],2)?></td>
					<td align="right"><?php print number_format($tmp["sgrand"],2)?></td>
					<td nowrap>
					<a href="report1.php?sdate=<?php print $date?>&edate=<?php print $date?>" class="btn btn-default btn-xs" role="button"><i class="glyphicon glyphicon-zoom-in"></i></a>
					</td>
				</tr>
		<?php 
				$stotal+= $tmp["stotal"];
				$sdelivery+= $tmp["sdelivery"];
				$sdiscount+= $tmp["sdiscount"];
				$sgrand+= $tmp["sgrand"];
			} 
		?>
				</tbody>
				<tfoot>
				<tr align="center">
					<td><strong>รวม</strong></td>
					<td align="right"><strong><?php print number_format($stotal,2)?></strong></td>
					<td align="right"><strong><?php print number_format($sdelivery,2)?></strong></td>
					<td align="right"><strong><?php print number_format($sdiscount,2)?></strong></td>
					<td align="right"><strong><?php print number_format($sgrand,2)?></strong></td>
					<td>เมนู</td>
				</tr>
				</tfoot>
			</table>
		
			<a type="button" class="btn btn-default btn-lg" href="print2.php?sdate=<?php print $_POST["sdate"]?>&edate=<?php print $_POST["edate"]?>" target="_blank"><i class="glyphicon glyphicon-print"></i></a>
			<a type="button" class="btn btn-default btn-sm" href="print2.php?sdate=<?php print $_POST["sdate"]?>&edate=<?php print $_POST["edate"]?>&type=excel"><img src="images/excel-icon.png"></a>
			<a type="button" class="btn btn-default btn-sm" href="print2.php?sdate=<?php print $_POST["sdate"]?>&edate=<?php print $_POST["edate"]?>&type=word"><img src="images/word-icon.png"></a>
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
/*
		$(document).ready(function() {
			var table = $('#data-tb').DataTable(
				{
					width : "100%",
					autoWidth : true,
					searching: false
				});
	   });
*/

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
