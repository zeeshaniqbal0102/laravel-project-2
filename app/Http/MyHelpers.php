<?php

	function escape($str, $allow_tags = false) {
		if($allow_tags == false) {
			$str = strip_tags($str);
		}
		
		return addslashes($str);
	}
	
	function unescape($str) {
		return stripslashes($str);
	}
	
	function date_difference($date1, $date2) {
		
		$dt1 = new DateTime($date1);
		$dt2 = new DateTime($date2);
		
		$difference = $dt1->diff($dt2);
		return $difference->format("%r%a");
	}
	
	function time_difference($time1, $time2, $format = '%h') {
		
		$t1 = new DateTime($time1);
		$t2 = new DateTime($time2);
		
		$difference = $t1->diff($t2);
		return $difference->format($format);
	}
	
	function dmy2ymd($date) {
		
		if(empty($date)) {
			return NULL;
		}
		
		$dt = explode("/", $date);
		return ($dt[2].'-'.$dt[1].'-'.$dt[0]);
	}