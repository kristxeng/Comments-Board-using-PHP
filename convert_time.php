<?php

function convert_time( $time ){
	//時區小時差
	$time_diff = 8;
	return date('Y-m-d H:i:s', ( strtotime($time) + $time_diff * 3600) );
	
};

?>