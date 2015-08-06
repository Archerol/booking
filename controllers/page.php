<?php

function PageController_index() {
	loadModule('orders');

	showTemplate('index');
}
