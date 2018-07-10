<?php
require_once 'vendor/autoload.php'; // Autoload files using Composer autoload

use KonradBaron\RedLine13\RedLine13;

$rl = new RedLine13('uhIkM08naxLLBNet5DFkUu7zwBh09kZDCHYoH6MSExEglTJqwpLqTTZquhrDCqhe');
echo $rl->getZipcodesByRadius(80403, 50);

//$teams = \GuzzleHttp\json_decode($fd->teamsAll('json'));

//echo implode(',', array_column($teams, 'Key'));