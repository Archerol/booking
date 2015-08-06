<?php
include( __DIR__ . '/../includes/core.php' );

loadModule('router');
Router_run(getConfig('router'));


