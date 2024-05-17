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
	 * Services:
	 */
	protected $Mailer;
	protected $Formix;
	protected $Ajaxer;

	/**
	 * @return Settings
	 */
	public function Settings()
	{
		return $this->Settings ?? $this->Settings = new Settings( $this );
	}

	/**
	 * @return Tools\Mailer
	 */
	public function Mailer()
	{
		return $this->Mailer ?? $this->Mailer = new Tools\Mailer( $this );
	}

	/**
	 * @return Tools\Formix
	 */
	public function Formix()
	{
		return $this->Formix ?? $this->Formix = new Tools\Formix( $this );
	}

	/**
	 * @return Tools\Ajaxer
	 */
	public function Ajaxer()
	{
		return $this->Ajaxer ?? $this->Ajaxer = new Tools\Ajaxer( $this );
	}
}
