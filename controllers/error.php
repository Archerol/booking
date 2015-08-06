<?php

function ErrorController_notFound() {
	showTemplate('error', [
		'errorText' => getMessage('page_not_found'),
	]);
}

