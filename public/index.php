<?php
include( __DIR__ . '/../includes/core.php' );


loadModule('user');
User_currentUser(User_getUserFromCookie());

loadModule('router');
Router_run(getConfig('router'));


