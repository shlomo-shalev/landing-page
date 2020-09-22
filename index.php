<?php

$for_time = 60 * 60 * 24 * 2;
session_set_cookie_params($for_time);
session_start();
session_regenerate_id();

$tomorrow_date = date('YmdHis', time() + (60 * 60) - (60 * 60 * 24));
$session_date = $_SESSION['date'] ?? 0;

if($session_date >= $tomorrow_date){ 

    include 'patience.html';

}else{

    include 'Vacation_for_Leads.html';

}