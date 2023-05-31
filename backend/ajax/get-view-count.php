<?php

use classes\Image;

require_once '../helper.php';

$image = new Image($_POST['image_id']);

jsonResponse($image->getViewCount());
