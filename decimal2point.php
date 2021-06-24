<?php
function decimal2point($number){
    $number = explode('.', $number);
    $new_number = $number[0];

    if(array_key_exists("1",$number)){
        $decimalVal = substr($number[1], 0, 1);
        if(!empty(substr($number[1], 1, 2))){
            $decimalVal .= substr($number[1], 1, 2);
        }
        if(empty(substr($number[1], 1, 2))){
            $decimalVal .= 0;
        }
        if(!empty(substr($number[1], 2, 3)) && substr($number[1], 2, 3) >= 5){
            $decimalVal++;
        }
        $new_number .= '.'.substr($decimalVal,0,2);
    }
    return $new_number;
}
?>