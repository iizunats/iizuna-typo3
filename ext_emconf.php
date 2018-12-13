<?php

$EM_CONF['ii_product'] = [
	'title'            => 'iizuna-typo3 example',
	'description'      => 'Example Extension for the iizuna-typo3 extension',
	'category'         => 'fe',
	'version'          => '0.0.1',
	'state'            => 'alpha',
	'clearcacheonload' => 1,
	'author'           => 'Tim Rücker',
	'author_email'     => 'tim.ruecker@iizunats.com',
	'constraints'      => [
		'depends' => [
			'typo3'  => '7.6.0-9.5.99',
			'php'    => '7.0.0-0.0.0',
			'iizuna' => '',
		],
	],
];