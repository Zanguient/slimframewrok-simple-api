<?php

class Initial {

        function index($param = null){
            if($param){
                echo $param;
            }else{
                echo 'index';
            }
        }
}
