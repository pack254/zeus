<?php
     //กำหนดให้ IE อ่าน page นี้ทุกครั้ง ไม่ไปเอาจาก cache
     header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
     header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
     header ("Cache-Control: no-cache, must-revalidate");
     header ("Pragma: no-cache");

	include("inc_connect.php"); 

	@extract($_REQUEST);

	switch ($cond) {

		// ยืนยันตัวตน
			case "customer" :
?>
			<table class="table table-bordered" id="data-tb" width="100%">
				<thead>
				<tr align="center">
					<td>#</td>
					<td align="left"><strong>ชื่อ-นามสกุล</strong></td>
					<td align="left"><strong>ที่อยู่</strong></td>
					<td align="left"><strong>เบอร์โทรศัพท์</strong></td>
					<td align="left"><strong>อีเมล</strong></td>
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
					<td align="left"><a href="javascript:load_customer(<?php print $tmp["cus_id"]?>,'<?php print $tmp["name"]?>','<?php print $tmp["address"]?>','<?php print $tmp["phone"]?>','<?php print $tmp["email"]?>');"><?php print $tmp["name"]?></a></td>
					<td align="left"><?php print $tmp["address"]?></td>
					<td align="left"><?php print $tmp["phone"]?></td>
					<td align="left"><?php print $tmp["email"]?></td>
					</td>
				</tr>
		<?php 	} ?>
				</tbody>
			</table>
<?php
			break;






			case "product":
?>
			<table class="table table-bordered" id="data-tb" width="100%">
				<thead>
				<tr align="center">
					<td align="left"><strong>รายการสินค้า</strong></td>
					<td align="left"><strong>รายการสินค้า</strong></td>
					<td align="left"><strong>รายการสินค้า</strong></td>
				</tr>
				</thead>
				<tbody>
		<?php 
			
			$SQL = "select * from tb_product order by code ";
			$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
			$i = 0;
			for($i=1;$i<=mysqli_num_rows($result);$i+=3) {
		?>
				<tr align="center">
<?php
				for($k=1;$k<=3;$k++) {
					$tmp = mysqli_fetch_array($result);
?>
					<td align="left">
			<?php if($tmp["code"]!="") { ?>
					<div class="col-lg-4">
						<?php if($tmp["picture"]!="") { ?><img src="images-upload/<?php print $tmp["picture"]?>" width="75" class="thumbnail"><?php } ?>
					</div>
					<div class="col-lg-8">
					<?php print $tmp["code"]?> <br/>
					<?php print $tmp["name"]?> <br/>
					ราคา <?php print number_format($tmp["price"],2)?> บาท<br/>
					คงเหลือ <?php print $tmp["qty"]?> ชิ้น
					</div>
					<?php if($tmp["qty"]>0) {?>
						<button type="button" class="btn btn-default btn-sm" onclick="load_product('<?php print $tmp["code"]?>','');"><i class="glyphicon glyphicon-shopping-cart"></i>
						<button type="button" class="btn btn-default btn-sm" onclick="load_product('<?php print $tmp["code"]?>','close'); "><i class="glyphicon glyphicon-shopping-cart"></i><i class="glyphicon glyphicon-arrow-down"></i>
					<?php } else { ?>
						<font color="red">สินค้าหมด</font>
					<?php } ?>
				<?php } ?>
					</td>
		<?php	} ?>
				</tr>
		<?php 	} ?>
				</tbody>
			</table>
<?php
			break;
			





			case "add_to_cart":
				$incart = "false";
				for($i=1;$i<=$_SESSION["CART_NO"];$i++) {
					if($_POST["val"]==$_SESSION["CART_CODE".$i]) {
						$incart = "true";
						$_SESSION["CART_QTY".$i]++;
						$_SESSION["CART_TOTAL".$i] = $_SESSION["CART_PRICE".$i]*$_SESSION["CART_QTY".$i];
						$_SESSION["CART_COST_TOTAL".$i] = $_SESSION["CART_COST".$i]*$_SESSION["CART_QTY".$i];
					}
				}
				if($incart=="false") {
					$SQL = "select * from tb_product where code='".$_POST["val"]."'";
					$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
					$tmp = mysqli_fetch_array($result);

					$_SESSION["CART_NO"]++;
					$i = $_SESSION["CART_NO"];

					$_SESSION["CART_CODE".$i] = $tmp["code"];
					$_SESSION["CART_NAME".$i] = $tmp["name"];
					$_SESSION["CART_PICTURE".$i] = $tmp["picture"];
					$_SESSION["CART_QTY".$i] = 1;
					$_SESSION["CART_PRICE".$i] = $tmp["price"];
					$_SESSION["CART_TOTAL".$i] = $tmp["price"];
					$_SESSION["CART_COST".$i] = $tmp["cost"];
					$_SESSION["CART_COST_TOTAL".$i] = $tmp["cost"];

				}
				session_write_close();
			break;






			case "load_cart":
?>
			<table class="table table-bordered" id="data-tb1" width="100%">
				<thead>
				<tr align="center">
					<td>#</td>
					<td align="center"><strong>รหัส/รูปสินค้า</strong></td>
					<td align="left"><strong>ชื่อสินค้า</strong></td>
					<td align="right"><strong>ราคา</strong></td>
					<td align="right"><strong>จำนวน</strong></td>
					<td align="right"><strong>รวมราคา</strong></td>
				</tr>
				</thead>
				<tbody>
<?php
				for($i=1;$i<=$_SESSION["CART_NO"];$i++) {

					$SQL = "select * from tb_product where code='".$_SESSION["CART_CODE".$i]."'";
					$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
					$tmp = mysqli_fetch_array($result);
?>
				<tr align="center">
					<td><?php print $i?></td>
					<td align="center"><?php print $_SESSION["CART_CODE".$i]?><?php if($_SESSION["CART_PICTURE".$i]!="") { ?><br/><img src="images-upload/<?php print $_SESSION["CART_PICTURE".$i]?>" width="75" class="thumbnail"><?php } ?></td>
					<td align="left"><?php print $_SESSION["CART_NAME".$i]?> <button type="button" class="btn btn-default btn-sm" onclick="remove_product(<?php print $i?>);"><i class="glyphicon glyphicon-trash"></i></a></td>
					<td align="right"><?php print $_SESSION["CART_PRICE".$i]?></td>
					<td align="right"><input type="number" id="qty<?php print $i?>" name="qty<?php print $i?>" value="<?php print $_SESSION["CART_QTY".$i]?>" class="text-right form-control" onchange="recal1(<?php print $i?>, $('#qty<?php print $i?>').val());" min="1" max="<?php print ($tmp["qty"]-$_SESSION["CART_QTY".$i])?>" step="1"></td>
					<td align="right"><?php print $_SESSION["CART_TOTAL".$i]?></td>
				</tr>
<?php		
					$qty+=$_SESSION["CART_QTY".$i];
					$total+=$_SESSION["CART_TOTAL".$i];
					$cost+=$_SESSION["CART_COST_TOTAL".$i];
				}	
?>
				</body>
				<tfoot>
				<tr>
					<td colspan="4" align="right"><strong>รวมเงิน</strong></td>
					<td align="right"><strong><?php print number_format($qty,0)?></strong></td>
					<td align="right"><strong><?php print number_format($total,2)?></strong></td>
				</tr>
				<tr>
					<td colspan="5" align="right"><strong>ค่าขนส่ง</strong></td>
					<td align="right"><input type="text" name="delivery" id="delivery" value="<?php print number_format($_SESSION["CART_DELIVERY"],2)?>" class="text-right form-control"></td>
				</tr>
				<tr>
					<td colspan="5" align="right"><strong>ส่วนลด</strong></td>
					<td align="right"><input type="text" name="discount" id="discount" value="<?php print number_format($_SESSION["CART_DISCOUNT"],2)?>" class="text-right form-control"></td>
				</tr>
				<tr>
					<td colspan="5" align="right"><strong>รวมเป็นเงินทั้งสิ้น</strong></td>
					<td align="right"><input type="text" name="grand" id="grand" value="<?php print number_format(($total+$_SESSION["CART_DELIVERY"])-$_SESSION["CART_DISCOUNT"],2)?>" class="text-right form-control"></td>
				</tr>
				</tfoot>
			</table>
			<input type="hidden" name="total" value="<?php print $total?>">
			<input type="hidden" name="cost" value="<?php print $cost?>">

			<script>
				$('#delivery').change(function() {
					recal();
				});
				$('#discount').change(function() {
					recal();
				});
			</script>
<?php
			break;




			case "recal": 
				$_SESSION["CART_DELIVERY"] = $_POST["delivery"];
				$_SESSION["CART_DISCOUNT"] = $_POST["discount"];
				$_SESSION["CART_GRAND"] = ($_SESSION["CART_GRAND"]+$_SESSION["CART_DELIVERY"])-$_SESSION["CART_DISCOUNT"];
				session_write_close();
			break;


			case "recal1": 
				$i = $_POST["no"];
				$_SESSION["CART_QTY".$i] = $_POST["qty"];
				$_SESSION["CART_TOTAL".$i] = $_SESSION["CART_PRICE".$i]*$_SESSION["CART_QTY".$i];
				$_SESSION["CART_COST_TOTAL".$i] = $_SESSION["CART_COST".$i]*$_SESSION["CART_QTY".$i];
				session_write_close();
			break;


			case "remove_product": 
				$i = $_POST["no"];
				for($i;$i<=$_SESSION["CART_NO"];$i++) {
					$_SESSION["CART_CODE".$i] = $_SESSION["CART_CODE".($i+1)];
					$_SESSION["CART_NAME".$i] = $_SESSION["CART_NAME".($i+1)];
					$_SESSION["CART_PICTURE".$i] = $_SESSION["CART_PICTURE".($i+1)];
					$_SESSION["CART_QTY".$i] = $_SESSION["CART_QTY".($i+1)];
					$_SESSION["CART_PRICE".$i] = $_SESSION["CART_PRICE".($i+1)];
					$_SESSION["CART_TOTAL".$i] = $_SESSION["CART_TOTAL".($i+1)];
					$_SESSION["CART_COST".$i] = $_SESSION["CART_COST".($i+1)];
					$_SESSION["CART_COST_TOTAL".$i] = $_SESSION["CART_COST_TOTAL".($i+1)];
				}
				$_SESSION["CART_NO"]--;
				session_write_close();
			break;

	}

?>