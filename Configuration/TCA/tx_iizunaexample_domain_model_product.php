<?php

return array(
	'ctrl'      => array(
		'title'                  => 'Product',
		'label'                  => 'title',
		'tstamp'                 => 'tstamp',
		'crdate'                 => 'crdate',
		'cruser_id'              => 'cruser_id',
		'dividers2tabs'          => true,
		'sortby'                 => 'sorting',
		'versioningWS'           => 2,
		'versioning_followPages' => true,

		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete'                   => 'deleted',
		'enablecolumns'            => array(
			'disabled'  => 'hidden',
			'starttime' => 'starttime',
			'endtime'   => 'endtime',
		),
		'setToDefaultOnCopy'       => 'registrations',
		'searchFields'             => 'title,description,',
		'iconfile'                 => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('iizuna_example') . 'Resources/Public/Icons/tx_iizunaexample_domain_model_product.gif',
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, description',
	),
	'types'     => array(
		'1' => array(
			'showitem' =>
				'sys_language_uid;;;;1-1-1,
				l10n_parent,
				l10n_diffsource,
				hidden;;1,
				title,
				description;;;richtext:rte_transform[mode=ts_links],
				--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,
				starttime,
				endtime
				',
		),
	),
	'palettes'  => array(
		'1' => array('showitem' => ''),
	),
	'columns'   => array(

		'sys_language_uid' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config'  => array(
				'type'       => 'select',
				'renderType' => 'selectSingle',
				'special'    => 'languages',
				'items'      => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1, 'flags-multiple',),
				),
				'default'    => 0,
			),
		),
		'l10n_parent'      => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config'      => array(
				'type'                => 'select',
				'renderType'          => 'selectSingle',
				'items'               => array(
					array('', 0),
				),
				'foreign_table'       => 'tx_iizunaexample_domain_model_product',
				'foreign_table_where' => 'AND tx_iizunaexample_domain_model_product.pid=###CURRENT_PID### AND tx_iizunaexample_domain_model_product.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource'  => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),

		't3ver_label' => array(
			'label'  => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max'  => 255,
			),
		),

		'hidden'      => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config'  => array(
				'type' => 'check',
			),
		),
		'starttime'   => array(
			'exclude'   => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label'     => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config'    => array(
				'type'     => 'input',
				'size'     => 13,
				'max'      => 20,
				'eval'     => 'datetime',
				'checkbox' => 0,
				'default'  => 0,
				'range'    => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
				),
			),
		),
		'endtime'     => array(
			'exclude'   => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label'     => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config'    => array(
				'type'     => 'input',
				'size'     => 13,
				'max'      => 20,
				'eval'     => 'datetime',
				'checkbox' => 0,
				'default'  => 0,
				'range'    => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
				),
			),
		),
		'tstamp'      => array(
			'exclude' => 1,
			'label'   => 'Creation date',
			'config'  => Array(
				'type'   => 'none',
				'format' => 'date',
				'eval'   => 'date',
			),
		),
		'title'       => array(
			'exclude' => 1,
			'label'   => 'Title',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required',
			),
		),
		'description' => array(
			'exclude' => 1,
			'label'   => 'Description',
			'config'  => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim',
			),
		),
	),
);