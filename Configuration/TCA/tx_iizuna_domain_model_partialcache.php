<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

return [
	'ctrl'      => [
		'title'                    => 'Partial Cache',
		'label'                    => 'path',
		'tstamp'                   => 'tstamp',
		'crdate'                   => 'crdate',
		'cruser_id'                => 'cruser_id',
		'dividers2tabs'            => true,
		'hideAtCopy'               => true,
		'sortby'                   => 'sorting',
		'versioningWS'             => 2,
		'versioning_followPages'   => true,
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete'                   => 'deleted',
		'enablecolumns'            => [
			'disabled'  => 'hidden',
			'starttime' => 'starttime',
			'endtime'   => 'endtime',
			'fe_group'  => 'fe_group',
		],
		'searchFields'             => 'clear_path,hash,partial',
		'iconfile'                 => \TYPO3\CMS\Core\Utility\PathUtility::getAbsoluteWebPath('iizuna') . 'Resources/Public/Icons/tx_iizuna_domain_model_partialcache.gif',
	],
	'interface' => [
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, clear_path, hash, partial',
	],
	'types'     => [
		'1' => [
			'showitem' => '
				sys_language_uid;;;;1-1-1,
				l10n_parent,
				l10n_diffsource,
				clear_path, 
				hash,
				partial
			--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,
				hidden;;1,
				starttime,
				endtime,
			--linebreak--,fe_group;LLL:EXT:cms/locallang_ttc.xlf:fe_group_formlabel,
			--linebreak--,editlock,
		',
		],
	],
	'palettes'  => [
		'1' => ['showitem' => ''],
	],
	'columns'   => [

		'sys_language_uid' => [
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config'  => [
				'type'       => 'select',
				'renderType' => 'selectSingle',
				'special'    => 'languages',
				'items'      => [
					['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1, 'flags-multiple',],
				],
				'default'    => 0,
			],
		],
		'l10n_parent'      => [
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config'      => [
				'type'                => 'select',
				'items'               => [
					['', 0],
				],
				'foreign_table'       => 'tx_iizuna_domain_model_partialcache',
				'foreign_table_where' => 'AND tx_iizuna_domain_model_partialcache.pid=###CURRENT_PID### AND tx_iizuna_domain_model_partialcache.sys_language_uid IN (-1,0)',
			],
		],
		'l10n_diffsource'  => [
			'config' => [
				'type' => 'passthrough',
			],
		],

		't3ver_label' => [
			'label'  => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'max'  => 255,
			],
		],

		'hidden'    => [
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config'  => [
				'type' => 'check',
			],
		],
		'starttime' => [
			'exclude'   => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label'     => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config'    => [
				'type'     => 'input',
				'size'     => 13,
				'max'      => 20,
				'eval'     => 'datetime',
				'checkbox' => 0,
				'default'  => 0,
				'range'    => [
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
				],
			],
		],
		'endtime'   => [
			'exclude'   => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label'     => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config'    => [
				'type'     => 'input',
				'size'     => 13,
				'max'      => 20,
				'eval'     => 'datetime',
				'checkbox' => 0,
				'default'  => 0,
				'range'    => [
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
				],
			],
		],
		'fe_group'  => [
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.fe_group',
			'config'  => [
				'type'                => 'select',
				'size'                => 5,
				'maxitems'            => 20,
				'items'               => [
					[
						'LLL:EXT:lang/locallang_general.xlf:LGL.hide_at_login',
						-1,
					],
					[
						'LLL:EXT:lang/locallang_general.xlf:LGL.any_login',
						-2,
					],
					[
						'LLL:EXT:lang/locallang_general.xlf:LGL.usergroups',
						'--div--',
					],
				],
				'exclusiveKeys'       => '-1,-2',
				'foreign_table'       => 'fe_groups',
				'foreign_table_where' => 'ORDER BY fe_groups.title',
			],
		],

		'clear_path' => [
			'exclude' => 1,
			'label'   => 'Path',
			'config'  => [
				'type' => 'input',
				'size' => 255,
				'eval' => 'trim',
			],
		],
		'hash'       => [
			'exclude' => 1,
			'label'   => 'Hash',
			'config'  => [
				'type' => 'input',
				'size' => 255,
				'eval' => 'trim',
			],
		],
		'partial'    => [
			'exclude' => 1,
			'label'   => 'Partial',
			'config'  => [
				'type' => 'input',
				'size' => 255,
				'eval' => 'trim',
			],
		],
	],
];
