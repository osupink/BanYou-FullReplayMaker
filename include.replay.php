<?php
function binstr($str) {
	$r='';
	$r.="\x0B".pack('c',strlen($str));
	$r.=$str;
	return $r;
}
function date_to_hex($seconds) {
	$hex="";
	// UTC = GMT+8 - 28800
	$seconds=(years_to_seconds(1970) + $seconds) * 10000000;
	$hex=pack("q",$seconds);
	/*
	$hexsplit=str_split(strrev(base_convert(number_format($seconds,0,'',''),10,16)),2);
	for ($i=0;$i<8;$i++) {
		if (strlen($hexsplit[$i]) == 1) {
			$hexsplit[$i]="0".$hexsplit[$i];
		} else {
			$hexsplit[$i]=strrev($hexsplit[$i]);
		}
		$hex.=pack("h*",$hexsplit[$i]);
	}
	*/
	return $hex;
}
function years_to_seconds($years) {
	/*
	$s=0;
	for ($i=1;$i<$year;$i++) {
		if (($i % 100 == 0 && ($i % 400 == 0 && $i % 3200 != 0)) || ($i % 4 == 0 && $i % 100 != 0)) {
			$s += 31622400;
		} else {
			$s += 31536000;
		}
	}
	return $s;
	*/
	if ($years === 1970) {
		return 62135596800;
	}
	return 31536000*($years-1)+(floor($years/4)-floor($years/100)+floor($years/400)-floor($years/3200))*86400;
}
function packreplay($mode,$username,$fileChecksum,$mods,$score,$maxcombo,$perfect,$rank,$countmiss,$count50,$count100,$count300,$countkatu,$countgeki,$repdata,$date) {
	$output='';
	$output.=pack('C',$mode);
	$output.=pack('I',20150414);
	$output.=binstr($fileChecksum);
	$output.=binstr($username);
	$output.=binstr(sprintf("%dp%do%do%dt%da%dr%de%sy%do%du%s%d%s",$count100+$count300,$count50,$countgeki,$countkatu,$countmiss,$fileChecksum,$maxcombo,($perfect ? "True" : "False"),$username,$score,$rank,$mods,"True"));
	$output.=pack('S',$count300);
	$output.=pack('S',$count100);
	$output.=pack('S',$count50);
	$output.=pack('S',$countgeki);
	$output.=pack('S',$countkatu);
	$output.=pack('S',$countmiss);
	$output.=pack('I',$score);
	$output.=pack('S',$maxcombo);
	$output.=pack('C',$perfect);
	$output.=pack('I',$mods);
	$output.=pack('C',0);
	$output.=date_to_hex($date);
	$output.=pack('I',strlen($repdata));
	$output.=$repdata;
	$output.=pack('I',0);
	$output.=pack('I',0);
	return $output;
}
?>
