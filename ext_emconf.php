<?php

$EM_CONF['iizuna'] = [
	'title'            => 'iizuna Template API',
	'description'      => 'A TYPO3 Extension that allows the use of server-side rendered but client side hydrated applications.',
	'category'         => 'fe',
	'version'          => '0.1.0',
	'state'            => 'beta',
	'clearcacheonload' => 1,
	'author'           => 'Tim Rücker',
	'author_email'     => 'tim.ruecker@iizunats.com',
	'constraints'      => [
		'depends' => [
			'typo3' => '6.2.0-9.5.99',
			'php'   => '7.0.0-0.0.0',
		],
	],
];