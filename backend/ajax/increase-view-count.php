<?php

use classes\Image;

require_once '../helper.php';

$image = new Image($_POST['image_id']);
$image->setIpAddress($_SERVER['REMOTE_ADDR']);
$image->setUserAgent($_SERVER['HTTP_USER_AGENT']);
$image->increaseViewCount();