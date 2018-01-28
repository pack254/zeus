<?php
	ob_start();
	session_cache_expire(270);
	session_start();
	date_default_timezone_set("Asia/Bangkok");

//	$elink = "http://localhost/zues/";

	$DB_HOSTNAME = "166.62.28.81";

	$DB_NAME = "prj_zues";
	$DB_USERNAME = "zues_admin_01";
	$DB_PASSWORD = "power254";

	$M_THAI_LONG = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
	$M_THAI_SHORT = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");	

/* #### connect DB ########################################## */

	$mysqli = mysqli_connect($DB_HOSTNAME, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
	if (!$mysqli) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL."<br>";
		echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL."<br>";
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL."<br>";
		exit;
	}
	mysqli_query($mysqli, "set names 'utf8' ");

/* #### End ########################################## */

	function alert($msg,$link) {
		print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"> \n
					<script>  \n
					<!-- Begin  \n
						alert('".$msg."');  \n";

		if($link=="next") {
			print "			// End -->  \n
					</script>  \n";

		} else if($link=="") { 
			print "	history.back();  \n
						// End -->  \n
					</script>  \n";
			exit();	

		} else { 
			print "	window.location.href='".$link."' \n
					// End -->  \n
					</script>  \n";
			exit();	
		}
	}	

	function show_en_date($tmp) {
		if($tmp!="0000-00-00" && $tmp!=""  && $tmp!="0000-00-00 00:00:00") {
			list($date,$time) = explode(" ",$tmp);
			list($y,$m,$d) = explode("-",$date);
			return $d."/".$m."/".$y;
		}
	}

	function show_date($tmp) {
		if($tmp!="0000-00-00" && $tmp!="" && $tmp!="0000-00-00 00:00:00") {
			list($date,$time) = explode(" ",$tmp);
			list($y,$m,$d) = explode("-",$date);
			$y += 543;
			return $d."/".$m."/".$y;
		}
	}
	function show_time($tmp) {
		if($tmp!="") {
			list($h,$m,$s) = explode(":",$tmp);
			return "$h:$m:$s";
		} else {
			return "00:00:00";
		}
	}

	function show_long_date($tmp) {
		if($tmp!="0000-00-00" && $tmp!=""  && $tmp!="0000-00-00 00:00:00") {
			global $M_THAI_LONG;
			list($date,$time) = explode(" ",$tmp);
			list($y,$m,$d) = explode("-",$date);
			$y += 543;
			return intval($d)." ".$M_THAI_LONG[intval($m)]." ".$y;
		}
	}

	function show_datetime($tmp) {
		list($date, $time)  = explode(" ", $tmp);
		return show_date($date)." ".show_time($time);
	}

	function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
	 {
		 $datetime1 = date_create($date_1);
		 $datetime2 = date_create($date_2);
		 
		 $interval = date_diff($datetime1, $datetime2);
		 
		 return $interval->format($differenceFormat);
		 
	 } 
?>