<?php
return [
	// This is how we show all of the $_GET
	'_GET'  => [
		'expose' => true,
	],
	
	// Let's hide passwords and expose the rest of $_POST
	'_POST'  => [
		'hide' => '/password/i', // yes, if you give me a string, you give me regex
		'expose' => true,
	],
	
	'_FILES'  => [
		'expose' => true,
	],
	
	// Cookies and session? Let's leave up the fields but hide the contents
	'_COOKIE'  => [
		'hide' => true,
	],
	
	'_SESSION'  => [
		'hide' => true,
	],
	
	// Let's show some of the servers variables for debugging and remove the rest
	'_SERVER'  => [
		'expose' => ['REDIRECT_STATUS', 'REQUEST_METHOD', 'QUERY_STRING', 'REQUEST_URI', ],
		'remove' => true,
	],
	
	// You could've skipped that remove as it would happen anyways for keys that didn't match anything before
	//Like this:
	'_ENV'  => [],
	
	'replaceHiddenValueWith' => '[hidden]',
	'replaceHiddenEmptyValueWith' => '',
];