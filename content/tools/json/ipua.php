<?php
$data = array();

$data['ip'] = $_SERVER['REMOTE_ADDR'];

if(isset($_SERVER['REMOTE_HOST'])) {
    $data['host'] = $_SERVER['REMOTE_HOST'];
}

$data['ua'] = $_SERVER['HTTP_USER_AGENT'];

echo json_encode($data);
?>
