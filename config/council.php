<?php

return [
	'recaptcha' => [
		'key' => env('RECAPTCHA_KEY'),
		'secret' => env('RECAPTCHA_SECRET')
	],
	'administrators' => [
		'harald@orgotech.com',
		'john@example.com'
	],
	'reputation' => [
		'reply_posted' => 2,
		'best_reply_awarded' => 50,
		'thread_was_published' => 10,
		'reply_favorited' => 5
	],
	'pagination' => [
		'perPage' => 25
	]
];