<?php include("inc_connect.php");?>
<?php if($_SESSION["LOGIN_OK"]!=true) { alert("คุณไม่มีสิทธิในการใช้งานเมนูนี้","main.php"); } ?>
<?php

	$_GET["sdate"] = ($_GET["sdate"]!="")?$_GET["sdate"]:date("Y-m-d", mktime(0,0,0,date("m")-1,date("d"),date("Y")) );
	$_GET["edate"] = ($_GET["edate"]!="")?$_GET["edate"]:date("Y-m-d");

	if($_GET["type"]!="") {
		header("Content-type: application/vnd.ms-".$_GET["type"]);
		header("Content-Disposition: attachment; filename=สรุปยอดขาย-".$_GET["sdate"]."-".$_GET["edate"].".".(($_GET["type"]=="word")?"doc":"xls"));
	}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <meta name="description" content="ระบบซื้อขายสินค้า">
    <meta name="author" content="zues.com">
	<meta name="keywords" content="ระบบซื้อขายสินค้า">

    <title>ระบบซื้อขายสินค้า</title>
	<link rel="shortcut icon" href="images/zues.jpg">


 </head>

  <body>
			<center>
			<h2> สรุปยอดขาย</h2>
			ระหว่างวันที่ <?php print show_date($_GET["sdate"])?>
			ถึงวันที่ <?php print show_date($_GET["edate"])?>
.			</center>
			<br/>
		 <table width="100%"  border="1" cellpadding="5" cellspacing="0">
				<thead>
				<tr align="center">
					<td align="center"><strong>วันที่ขาย</strong></td>
					<td align="right"><strong>รวมเงิน</strong></td>
					<td align="right"><strong>ค่าขนส่ง</strong></td>
					<td align="right"><strong>ส่วนลด</strong></td>
					<td align="right"><strong>รวมเงินทั้งสิ้น</strong></td>
				</tr>
				</thead>
				<tbody>
		<?php 
			
			list($y,$m,$d) = explode("-",$_GET["sdate"]);
			list($y1,$m1,$d1) = explode("-",$_GET["edate"]);

			if(($y.$m.$d) > ($y1.$m1.$d1)) {
				$s = $_GET["sdate"];
				$_GET["sdate"] = $_GET["edate"];
				$_GET["edate"] = $s;
			}
	
			$max = dateDifference($_GET["edate"], $_GET["sdate"], '%a');
#print $max;

			$SQL = "select sum(total) as stotal, sum(delivery) as sdelivery, sum(discount) as sdiscount, sum(grand) as sgrand from tb_sale where lastupdate between '".$_GET["sdate"]." 00:00:00' and '".$_GET["edate"]." 23:59:59'  ";
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
					<td align="left"><?php print show_date($date)?><div style="width:<?php print $percent?>%; background-color:green">&nbsp;</div></td>
					<td align="right"><?php print number_format($tmp["stotal"],2)?></td>
					<td align="right"><?php print number_format($tmp["sdelivery"],2)?></td>
					<td align="right"><?php print number_format($tmp["sdiscount"],2)?></td>
					<td align="right"><?php print number_format($tmp["sgrand"],2)?></td>
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