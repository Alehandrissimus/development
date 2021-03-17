<?php
$methods = [
	'submitAmbassador' => [
		'params' => [
			[
				'name' => 'firstname',
				'source' => 'p',
				'pattern' => '',
				'required' => true
			],
			[
				'name' => 'secondname',
				'source' => 'p',
				'pattern' => '',
				'required' => true
			],
			[
				'name' => 'position',
				'source' => 'p',
				'pattern' => '',
				'required'  => false 
			],
			[
				'name' => 'phone',
				'source' => 'p',
				'pattern' => '[5-9][0-9]{8}\b',
				'required' => true
			],
		]
	],
];