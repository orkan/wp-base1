<?php
/*
 * This file is part of the orkan/wp-base1 package.
 * Copyright (c) 2024 Orkan <orkans+wpbase1@gmail.com>
 */

/**
 * Settings tool: Ajaxer.
 */
return [
	'info' => [
		'type'   => 'html',
		'html'   => <<<HTML
		<h2>Ajax examples</h2>
		Choose any of Plugin::cfg() keys to display coresponding value.<br>
		Enable JS verbosing by typing in console: ork.debug=1<br>
		HTML,
		'desc'   => 'Inputs defined in: ' . __FILE__,
	],
	'selector' => [
		'type'  => 'group',
		'items' => [
			[
				'type'   => 'html',
				'html'   => '<hr><h2>Get current Plugin::cfg() value</h2>',
			],
			'selector_select' => [
				'type'  => 'select',
				'label' => 'Choose ',
				'items' => [ '' => 'Config:' ],
			],
			'selector_spinner' => [
				'type' => 'html',
				'html' => '<span id="selector_spin" class="ork-spinner"></span>',
			],
			'selector_result' => [
				'type'     => 'text',
				'label'    => 'Response: ',
				'style'    => 'width: 30em;',
				'readonly' => true,
			],
		],
	],
	'texter' => [
		'type'  => 'group',
		'items' => [
			[
				'type'   => 'html',
				'html'   => '<hr><h2>Send user input to server. Wait for read back. Delete previous request if in progress.</h2>',
			],
			'texter_error' => [
				'type' => 'checkbox',
				'tag'  => 'Throw errors instead of normal response',
			],
			'texter_text' => [
				'type'  => 'text',
				'label' => 'Type: ',
				'style' => 'width: 30em;',
			],
			'texter_spinner' => [
				'type' => 'html',
				'html' => '<span id="texter_spin" class="ork-spinner"></span>',
			],
			'texter_result' => [
				'type'     => 'text',
				'label'    => 'Response: ',
				'style'    => 'width: 30em;',
				'readonly' => true,
			],
		],
	],
	'cache' => [
		'type'  => 'group',
		'items' => [
			[
				'type'   => 'html',
				'html'   => '<hr><h2>Clear DB record holding cached inputs definition of this page</h2>',
			],
			'cache_btn' => [
				'type'  => 'button',
				'class' => 'button',
				'tag'   => 'Reload page inputs',
			],
			'cache_spinner' => [
				'type' => 'html',
				'html' => '<span id="cache_spin" class="ork-spinner"></span>',
			],
			'cache_result' => [
				'type' => 'html',
				'html' => '<div id="cache_result"></div>',
			],
		],
	],
];
