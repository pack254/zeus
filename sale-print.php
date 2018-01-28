<?php include("inc_connect.php");?>
<?php

	$SQL = "select * from tb_sale where sale_id='".$_GET["sale_id"]."'";
	$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
	$tmp = mysqli_fetch_array($result);
	
	$SQL = "select * from tb_customer where cus_id='".$tmp["cus_id"]."'";
	$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
	$tmp1 = mysqli_fetch_array($result);

?>
<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>ใบเสร็จรับเงิน</title>
 </head>
 <body>
 <center><h2>ใบเสร็จรับเงิน</h2></center>
 <p align="center">ชื่อร้านค้า ที่อยู่ เบอร์โทรศัพท์</p>
 <table width="100%"  border="0">
   <tr>
     <td valign="top"><table width="100%"  border="0">
       <tr>
         <td colspan="2"><h3>ข้อมูลลูกค้า</h3></td>
        </tr>
       <tr>
         <td width="25%">ชื่อ-นามสกุล : </td>
         <td>&nbsp;<?php print $tmp1["name"]?></td>
       </tr>
       <tr>
         <td>ที่อยู่ : </td>
         <td>&nbsp;<?php print $tmp1["address"]?></td>
       </tr>
       <tr>
         <td nowrap>เบอร์โทรศัพท์ : </td>
         <td>&nbsp;<?php print $tmp1["phone"]?></td>
       </tr>
       <tr>
         <td>อีเมล์ : </td>
         <td>&nbsp;<?php print $tmp1["email"]?></td>
       </tr>
     </table></td>
     <td valign="top" width="40%"><table width="100%"  border="0">
       <tr>
         <td colspan="2">&nbsp;</td>
        </tr>
       <tr>
         <td colspan="2">&nbsp;</td>
        </tr>
       <tr>
         <td colspan="2">&nbsp;</td>
        </tr>
       <tr>
         <td width="35%" nowrap>เลขที่ใบเสร็จ : </td>
         <td>&nbsp;<?php print str_pad($tmp["sale_id"], 5, "0", STR_PAD_LEFT);?></td>
       </tr>
       <tr>
         <td>วันที่/เวลา : </td>
         <td>&nbsp;<?php print show_datetime($tmp["lastupdate"])?> น.</td>
       </tr>
     </table></td>
   </tr>
 </table>
 <h3>ข้อมูลสินค้า</h3>
 <table width="100%"  border="1" cellpadding="5" cellspacing="0">
   <tr align="center">
     <td width="10%"><strong>ลำดับ</strong></td>
     <td><strong>รายการ</strong></td>
     <td width="20%"><strong>ราคา</strong></td>
     <td width="20%"><strong>จำนวน</strong></td>
     <td width="20%"><strong>รวมราคา</strong></td>
   </tr>
<?php
	$SQL = "select d.*, p.name from tb_sale_detail as d, tb_product as p where d.sale_id='".$tmp["sale_id"]."' and d.code=p.code";
	$result = mysqli_query($mysqli, $SQL) or die(mysqli_error($mysqli).$SQL);
	$i = 0;
	while($tmp1 = mysqli_fetch_array($result)) {
		$i++;
?>
   <tr>
     <td align="center"><?php print $i?></td>
     <td><?php print $tmp1["name"]?></td>
     <td align="right"><?php print $tmp1["price"]?></td>
     <td align="right"><?php print $tmp1["qty"]?></td>
     <td align="right"><?php print $tmp1["total"]?></td>
   </tr>
<?php } ?>
   <tr align="right">
     <td colspan="4"><strong>ค่าขนส่ง : </strong></td>
     <td><strong><?php print number_format($tmp["delivery"],2)?></strong></td>
   </tr>
   <tr align="right">
     <td colspan="4"><strong>ส่วนลด : </strong></td>
     <td><strong><?php print number_format($tmp["discount"],2)?></strong></td>
   </tr>
   <tr align="right">
     <td colspan="4"><strong>รวมเป็นเงินทั้งสิ้น : (<?php print Convert(number_format($tmp["grand"],2))?>) </strong></td>
     <td><strong><?php print number_format($tmp["grand"],2)?></strong></td>
   </tr>
 </table>
 <p align="left">&nbsp;</p>
 <table width="100%"  border="0">
   <tr>
     <td align="center">ลงชื่อ ....................................... ผู้รับ </td>
     <td align="center">ลงชื่อ ....................................... ผู้ขาย</td>
   </tr>
   <tr>
     <td align="center">..........................................</td>
     <td align="center">........................................</td>
   </tr>
   <tr>
     <td align="center">วันที่ ...........................................</td>
     <td align="center">วันที่ ...........................................</td>
   </tr>
 </table>
 <p>&nbsp;</p>
 </body>
</html>
<?php
function Convert($amount_number)
{
    $amount_number = number_format($amount_number, 2, ".","");
    $pt = strpos($amount_number , ".");
    $number = $fraction = "";
    if ($pt === false) 
        $number = $amount_number;
    else
    {
        $number = substr($amount_number, 0, $pt);
        $fraction = substr($amount_number, $pt + 1);
    }
    
    $ret = "";
    $baht = ReadNumber ($number);
    if ($baht != "")
        $ret .= $baht . "บาท";
    
    $satang = ReadNumber($fraction);
    if ($satang != "")
        $ret .=  $satang . "สตางค์";
    else 
        $ret .= "ถ้วน";
    return $ret;
}

function ReadNumber($number)
{
    $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
    $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
    $number = $number + 0;
    $ret = "";
    if ($number == 0) return $ret;
    if ($number > 1000000)
    {
        $ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
        $number = intval(fmod($number, 1000000));
    }
    
    $divider = 100000;
    $pos = 0;
    while($number > 0)
    {
        $d = intval($number / $divider);
        $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" : 
            ((($divider == 10) && ($d == 1)) ? "" :
            ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
        $ret .= ($d ? $position_call[$pos] : "");
        $number = $number % $divider;
        $divider = $divider / 10;
        $pos++;
    }
    return $ret;
}
?>

<script>
	window.print();
</script>