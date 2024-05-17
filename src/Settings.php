<?php
/*
 * This file is part of the orkan/wp-base1 package.
 * Copyright (c) 2024 Orkan <orkans+wpbase1@gmail.com>
 */
namespace Orkan\WP\Base1;

/**
 * Plugin: Base 1.
 *
 * @author Orkan <orkans+wpbase1@gmail.com>
 */
class Settings extends \Orkan\WP\Base\Settings
{
	/*
	 * Transient messages.
	 */
	protected $transients = [
		'activate' => 'Please open {link} page to finish plugin activation!',
	];

	/**
	 * Activate plugin.
	 * @see \Orkan\WP\Base\Settings::actionActivate()
	 */
	public function actionActivate()
	{
		$this->adminTransient( 'activate', [ 'tokens' => [ '{link}' => $this->toolLink( 'settings' ) ] ] );
	}

	/**
	 * Update inputs before displaying.
	 * @see Settings::getInputFields()
	 */
	public function filterInputFields( array $fields ): array
	{
		$fields = parent::filterInputFields( $fields );

		/* @formatter:off */
		$fields['group_b1']['items']['group_b1_nested']['desc'] = 'Items generated in ' . __METHOD__;
		$fields['group_b1']['items']['group_b1_nested']['items'] = [
			'group_b1_nested_text' => [
				'type'  => 'text',
				'title' => 'Title 1',
				'class' => 'large-text',
				'hint'  => 'Im [text] inside [Groupped/Nested]',
			],
			'group_b1_nested_cbox' => [
				'type'  => 'checkbox',
				'title' => 'Title 2',
				'tag'   => 'Im [checkbox] inside [Groupped/Nested]',
			],
		];
		/* @formatter:on */

		return $fields;
	}
}
