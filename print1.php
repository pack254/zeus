<?php include("inc_connect.php");?>
<?php if($_SESSION["LOGIN_OK"]!=true) { alert("คุณไม่มีสิทธิในการใช้งานเมนูนี้","main.php"); } ?>
<?php

	$_GET["sdate"] = ($_GET["sdate"]!="")?$_GET["sdate"]:date("Y-m-d");
	$_GET["edate"] = ($_GET["edate"]!="")?$_GET["edate"]:date("Y-m-d");

	if($_GET["type"]!="") {
		header("Content-type: application/vnd.ms-".$_GET["type"]);
		header("Content-Disposition: attachment; filename=รายงานการขาย-".$_GET["sdate"]."-".$_GET["edate"].".".(($_GET["type"]=="word")?"doc":"xls"));
	}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <meta name="description" content="ระบบซื้อขายสินค้า">
    <meta name="author" content="zues.com">
	<meta name="keywords" content="ระบบซื้อขายสินค้า">

    <title>รายงานการขาย</title>
	<link rel="shortcut icon" href="images/zues.jpg">


 </head>

  <body>
		

		<div class="col-xs-12">
			<center>
			<h2> รายงานการขาย</h2>
			ระหว่างวันที่ <?php print show_date($_GET["sdate"])?>
			ถึงวันที่ <?php print show_date($_GET["edate"])?>
.			</center>
			<br/>
		 <table width="100%"  border="1" cellpadding="5" cellspacing="0">
				<thead>
				<tr align="center">
					<td>#</td>
					<td align="center"><strong>วันที่ขาย</strong></td>
					<td align="left"><strong>ข้อมูลลูกค้า</strong></td>
					<td align="right"><strong>รวมเงิน</strong></td>
					<td align="right"><strong>ค่าขนส่ง</strong></td>
					<td align="right"><strong>ส่วนลด</strong></td>
					<td align="right"><strong>รวมเงินทั้งสิ้น</strong></td>
					<td align="left"><strong>หมายเหตุ</strong></td>
				</tr>
				</thead>
				<tbody>
		<?php 
			
			$SQL = "select * from tb_sale where lastupdate between '".$_GET["sdate"]." 00:00:00' and '".$_GET["edate"]." 23:59:59' order by lastupdate desc ";
			$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
			$i = 0;
			while($tmp = mysqli_fetch_array($result)) {
				$i++;

				$SQL = "select * from tb_customer where cus_id='".$tmp["cus_id"]."'";
				$result1 = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
				$tmp1 = mysqli_fetch_array($result1);
		?>
				<tr align="center">
					<td><?php print $i?></td>
					<td align="center"><?php print show_datetime($tmp["lastupdate"])?> น.</td>
					<td align="left"><?php print $tmp1["name"]?> <?php if($tmp1["phone"]!="") { ?>(<?php print $tmp1["phone"]?>)<?php } ?></td>
					<td align="right"><?php print number_format($tmp["total"],2)?></td>
					<td align="right"><?php print number_format($tmp["delivery"],2)?></td>
					<td align="right"><?php print number_format($tmp["discount"],2)?></td>
					<td align="right"><?php print number_format($tmp["grand"],2)?></td>
					<td align="left"><?php print $tmp["note"]?></td>
				</tr>
		<?php 
				$total+= $tmp["total"];
				$delivery+= $tmp["delivery"];
				$discount+= $tmp["discount"];
				$grand+= $tmp["grand"];
		} ?>
				</tbody>
				<tfoot>
				<tr align="center">
					<td colspan="3"><strong>รวม</strong></td>
					<td align="right"><strong><?php print number_format($total,2)?></strong></td>
					<td align="right"><strong><?php print number_format($delivery,2)?></strong></td>
					<td align="right"><strong><?php print number_format($discount,2)?></strong></td>
					<td align="right"><strong><?php print number_format($grand,2)?></strong></td>
					<td>หมายเหตุ</td>
				</tr>
				</tfoot>
			</table>
		

  </body>
</html>
<?php if($_GET["type"]=="") { ?>
<script>
	window.print();
</script>
<?php } ?>