<?php
function urlMaker($cno,$type){
	if ($type == 'original'){
		if (strpos($cno,'-A') == false)
			return $cno;
		else {
			$tmp = explode('-',$cno);
			return $tmp[0];
		}
	}
	else if ($type == 'arused'){
		if (strpos($cno,'-A') == false)
			return $cno.'-A';
		else {
			return $cno;
		}
	}
	else if ($type == 'what'){
		if (strpos($cno,'-A') == false)
			return 0;
		else 
			return 1;
	}
}

function displayArused($cnoArused){
	$sqlA = "SELECT cNo FROM `char` WHERE cNo = '" .$cnoArused. "'";
	$resultA = mysql_query($sqlA) or die('MySQL query error');
	if (mysql_num_rows($resultA)==0)
		echo 'display:none;';
}

function jobColor ($job){
	if ($job == '劍')
		return 'bg-swordman';
	if ($job == '拳')
		return 'bg-fighter';
	if ($job == '斧')
		return 'bg-warrior';
	if ($job == '槍')
		return 'bg-lanser';
	if ($job == '弓')
		return 'bg-archer';
	if ($job == '法')
		return 'bg-magician';
	if ($job == '雙刀')
		return 'bg-crosssaber';
}
function jobStandFor($job){
	switch ($job){
		case '劍':
			return '劍士';
		case '拳':
			return '格鬥家';
		case '斧':
			return '戰士';
		case '槍':
			return '槍兵';
		case '弓':
			return '弓兵';
		case '法':
			return '魔導士';
		case '雙刀':
			return '雙刀';
		default:
			return '-';
	}
}

function setDSsEffect($row){
	$dsArr = array($row['ps1'],$row['ps2'],$row['ps3']);
	$dsPreArr = array(1,1,1,1,1);
	$maxArr = array($row['hp100'], $row['sp100'], $row['atk100'], $row['def100'], $row['cri100']);
	$hyperArr = array($row['hpHyper'], $row['spHyper'], $row['atkHyper'], $row['defHyper'], $row['criHyper']);
	$changeFlag = array(0,0,0,0,0);
	//echo '<br/>';
	foreach ($dsArr as $i)
	{
		/* ========== HP ========== */
		if (strcmp($i,'HP+10%')==0){
				$dsPreArr[0]+=.10;
		}
		else if (strcmp($i,'HP+15%')==0){
				$dsPreArr[0]+=.15;
		}
		else if (strcmp($i,'HP+20%')==0){
				$dsPreArr[0]+=.20;
		}
		
		/* ========== SP ========== */
		if (strcmp($i,'SP+10%')==0){
				$dsPreArr[1]+=.10;
		}
		else if (strcmp($i,'SP+15%')==0){
				$dsPreArr[1]+=.15;
		}
		else if (strcmp($i,'SP+20%')==0){
				$dsPreArr[1]+=.20;
		}
		
		/* ========== ATK ========== */
		if (strcmp($i,'攻擊+10%')==0){
				$dsPreArr[2]+=.10;
		}
		else if (strcmp($i,'攻擊+15%')==0){
				$dsPreArr[2]+=.15;
		}
		else if (strcmp($i,'攻擊+20%')==0){
				$dsPreArr[2]+=.20;
		}
		
		/* ========== DEF ========== */
		if (strcmp($i,'防御+10%')==0){
				$dsPreArr[3]+=.10;
		}
		else if (strcmp($i,'防禦+10%')==0){
				$dsPreArr[3]+=.10;
		}
		else if (strcmp($i,'防御+15%')==0){
				$dsPreArr[3]+=.15;
		}
		else if (strcmp($i,'防禦+15%')==0){
				$dsPreArr[3]+=.15;
		}
		else if (strcmp($i,'防御+20%')==0){
				$dsPreArr[3]+=.20;
		}
		else if (strcmp($i,'防禦+20%')==0){
				$dsPreArr[3]+=.20;
		}
		
		/* ========== CRI ========== */
		if (strcmp($i,'會心+10%')==0){
				$dsPreArr[4]+=.10;
		}
		else if (strcmp($i,'會心+15%')==0){
				$dsPreArr[4]+=.15;
		}
		else if (strcmp($i,'會心+20%')==0){
				$dsPreArr[4]+=.20;
		}
	}
	
	for ($i=0; $i<5; $i++)
	{
		$maxArr[$i]=$maxArr[$i]*$dsPreArr[$i];
		if (substr_count($maxArr[$i], '.')>0)
			$maxArr[$i]=floor($maxArr[$i]);
		$hyperArr[$i]=$hyperArr[$i]*$dsPreArr[$i];
		if (substr_count($hyperArr[$i], '.')>0)
			$hyperArr[$i]=floor($hyperArr[$i]);
		if ($dsPreArr[$i] > 1)
			$changeFlag[$i] = 1;
	}
	//print_r($changeFlag);
	$spArr = array($row['sp1'],$row['sp2'],$row['sp3']);
	$spArr[0]*=$dsPreArr[2];
	$spArr[1]*=$dsPreArr[2];
	$spArr[2]*=$dsPreArr[2];
	if (substr_count($spArr[0], '.')>0)
		$spArr[0]=floor($spArr[0]);
	if (substr_count($spArr[1], '.')>0)
		$spArr[1]=floor($spArr[1]);
	if (substr_count($spArr[2], '.')>0)
		$spArr[2]=floor($spArr[2]);
	
	return array($changeFlag, $maxArr, $hyperArr, $spArr);
}

function printDSsEffect($arr, $key, $type){
	if ($arr[0][0] == 1){
		if ($key == 'max' && $type == 'hp')
			echo ' ('.$arr[1][0].')';
		if ($key == 'hyper' && $type == 'hp')
			echo ' ('.$arr[2][0].')';
	}
	if ($arr[0][1] == 1){
		if ($key == 'max' && $type == 'sp')
			echo ' ('.$arr[1][1].')';
		if ($key == 'hyper' && $type == 'sp')
			echo ' ('.$arr[2][1].')';
	}
	if ($arr[0][2] == 1){
		if ($key == 'max' && $type == 'atk')
			echo ' ('.$arr[1][2].')';
		if ($key == 'hyper' && $type == 'atk')
			echo ' ('.$arr[2][2].')';
	}
	if ($arr[0][3] == 1){
		if ($key == 'max' && $type == 'def')
			echo ' ('.$arr[1][3].')';
		if ($key == 'hyper' && $type == 'def')
			echo ' ('.$arr[2][3].')';
	}
	if ($arr[0][4] == 1){
		if ($key == 'max' && $type == 'cri')
			echo ' ('.$arr[1][4].')';
		if ($key == 'hyper' && $type == 'cri')
			echo ' ('.$arr[2][4].')';
	}
	
	if ($key == 0 && $type == 'spr')
		echo $arr[1][1];
	if ($key == 1 && $type == 'spr')
		echo $arr[3][0];
	if ($key == 2 && $type == 'spr')
		echo $arr[3][1];
	if ($key == 3 && $type == 'spr')
		echo $arr[3][2];
	if ($key == 4 && $type == 'spr')
		echo $arr[2][1];
}

?>