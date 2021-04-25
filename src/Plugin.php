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
class Plugin extends \Orkan\WP\Base\Plugin
{
	const NAME = 'Ork Base1 example';

	public static function actionAdminEnqueueScripts( $page ): void
	{
		wp_add_inline_script( 'wp-util', sprintf( 'console.log("Plugin [%s] is active!");', static::$Factory->get( 'name' ) ) );
	}
}
