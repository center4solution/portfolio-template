<?php
// Front controller for this design version. Code, data, blog content and images
// live in ../shared; this folder only holds its own views and css/js.
define('VERSION_DIR', __DIR__);
require dirname(__DIR__) . '/shared/app/router.php';
