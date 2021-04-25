<?php
/*
 * This file is part of the orkan/wp-base1 package.
 * Copyright (c) 2024 Orkan <orkans+wpbase1@gmail.com>
 */
namespace Orkan\WP\Base1;

/**
 * Plugin: Factory.
 *
 * @author Orkan <orkans+wpbase1@gmail.com>
 */
class Factory extends \Orkan\WP\Base\Factory
{
	/**
	 * @return Plugin
	 */
	public static function Plugin()
	{
		return static::$Plugin ?? static::$Plugin = new Plugin( static::Factory() );
	}
}
