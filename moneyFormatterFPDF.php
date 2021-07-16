<?php
function numberToCurrency($number)
{
    $checkMinusVal = trim(explode('-',$number)[0]);
    $checkMinus = $final = '';
    $allStr = explode('.',trim($number));
    if($checkMinusVal == ''){
        $checkMinus = '-';
        $allStr = explode('.',explode('-',trim($number))[1]);
    }
    $str = $allStr[0];
    $length = strlen($str);
    $count = 1;
    $first = 0;
	$hasMoreNumber = 0;
    for($i = $length-1; $i >= 0; $i--){   
        if($count == 3 && $first == 0){
            $final .= $str[$i];
            $count = 0;
            $first = 1;
            $hasMoreNumber = 1;
        }
        elseif($count == 2 && $first == 1){
            if( ($i - 1) < 0){
                $final .= $str[$i];
            }
            else{
                $final .= $str[$i].',';
            }
            $count = 0;
        }
        else{
        	if($hasMoreNumber == 1){
              	$final .=',';
            	$hasMoreNumber = 0;
            }
            $final .= $str[$i];
        } 
        $count++;
    }
    $final = strrev($final);
    if(array_key_exists("1",$allStr)){
        $decimalVal = $allStr[1][0];
        if(!empty($allStr[1][1])){
            $decimalVal .= $allStr[1][1];
        }
        else{
            $decimalVal .= 0;
        }
        if(!empty($allStr[1][2]) && $allStr[1][2] >= 5){
            $decimalVal++;
        }
        $final .= '.'.$decimalVal;
    }
    return $checkMinus.$final.'/-';
}
?>