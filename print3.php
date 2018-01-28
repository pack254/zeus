<?php include("inc_connect.php");?>
<?php if($_SESSION["LOGIN_OK"]!=true) { alert("คุณไม่มีสิทธิในการใช้งานเมนูนี้","main.php"); } ?>
<?php

	$_GET["sdate"] = ($_GET["sdate"]!="")?$_GET["sdate"]:date("Y-m-d", mktime(0,0,0,date("m")-1,date("d"),date("Y")) );
	$_GET["edate"] = ($_GET["edate"]!="")?$_GET["edate"]:date("Y-m-d");

	if($_GET["type"]!="") {
		header("Content-type: application/vnd.ms-".$_GET["type"]);
		header("Content-Disposition: attachment; filename=สินค้าขายดี-".$_GET["sdate"]."-".$_GET["edate"].".".(($_GET["type"]=="word")?"doc":"xls"));
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
			<h2>สินค้าขายดี</h2>
			ระหว่างวันที่ <?php print show_date($_GET["sdate"])?>
			ถึงวันที่ <?php print show_date($_GET["edate"])?>
.			</center>
			<br/>
		 <table width="100%"  border="1" cellpadding="5" cellspacing="0">
				<thead>
				<tr align="center">
					<td align="center"><strong>#</strong></td>
					<td align="center"><strong>รหัส/รูปสินค้า</strong></td>
					<td align="right"><strong>ชื่อสินค้า</strong></td>
					<td align="right"><strong>ราคา</strong></td>
					<td align="right"><strong>จำนวนที่ขายได้</strong></td>
					<td align="right"><strong>รวมเงิน</strong></td>
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
				
			$SQL = "select sum(d.qty) as sqty,d.code, d.price from tb_sale_detail as d, tb_sale as s where s.lastupdate between '".$_GET["sdate"]." 00:00:00' and '".$_GET["edate"]." 23:59:59'  and s.sale_id=d.sale_id group by d.code order by sqty desc ";
			$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
			$i = 0;
			while($tmp = mysqli_fetch_array($result)) {
				$i++;

				$SQL = "select * from tb_product where code='".$tmp["code"]."'";
				$result1 = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
				$tmp1 = mysqli_fetch_array($result1);
		?>
				<tr align="center">
					<td align="center"><?php print $i?></td>
					<td align="center"><?php print $tmp["code"]?><?php if($tmp1["picture"]!="" && $_GET["type"]=="") { ?><br/><img src="images-upload/<?php print $tmp1["picture"]?>" width="75"><?php } ?></td>
					<td align="left"><?php print $tmp1["name"]?></td>
					<td align="right"><?php print number_format($tmp["price"],2)?></td>
					<td align="right"><?php print number_format($tmp["sqty"],0)?></td>
					<td align="right"><?php print number_format($tmp["price"]*$tmp["sqty"],2)?></td>
				</tr>
		<?php 
				$qty+= $tmp["sqty"];
				$total+= $tmp["sqty"]*$tmp["price"];
			} 
		?>
				</tbody>
				<tfoot>
				<tr align="center">
					<td colspan="4"><strong>รวม</strong></td>
					<td align="right"><strong><?php print number_format($qty,0)?></strong></td>
					<td align="right"><strong><?php print number_format($total,2)?></strong></td>
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