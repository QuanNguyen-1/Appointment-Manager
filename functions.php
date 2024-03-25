<?php
    function cleanNum($num){
        if ($num[3] == "-" && $num[7] == "-"){
            return $num;
        } else {
            return substr($num, 0, 3) . "-" . substr($num, 3, 3) . "-" . substr($num,6);
        }
    }

    function cleanInput($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }