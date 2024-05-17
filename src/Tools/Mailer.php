<?php
/*
 * This file is part of the orkan/wp-base1 package.
 * Copyright (c) 2024 Orkan <orkans+wpbase1@gmail.com>
 */
namespace Orkan\WP\Base1\Tools;

use Orkan\Input;
use Orkan\Inputs;

/**
 * Settings tool: Mailer.
 * Example of using wp_mail() within plugin Tool page.
 *
 * @author Orkan <orkans+wpbase1@gmail.com>
 */
class Mailer extends \Orkan\WP\Base\Tools
{
	const TITLE = 'Mailer';
	const NAME = 'mailer';
	const ICON = 'email-alt';
	const MENU = true;

	/**
	 * Connected?
	 */
	private $ready = false;

	/**
	 * Register all hooks.
	 */
	public function run(): void
	{
		if ( $this->ready ) {
			return;
		}

		parent::run();

		if ( $this->isPage ) {
			add_action( $this->Settings->getHookName( 'submit' ), [ $this, 'actionSubmitMailer' ] );
			add_action( $this->Settings->getHookName( 'render' ), [ $this, 'actionRenderMailer' ] );
			add_action( 'wp_mail_failed', [ $this, 'actionWpMailFailed' ] );
		}

		$this->ready = true;
	}

	/**
	 * Show more detailed error message.
	 */
	public function actionWpMailFailed( \WP_Error $E )
	{
		foreach ( $E->get_error_messages() as $msg ) {
			$this->Settings->adminNotice( 'Mail', $msg, [ 'type' => 'error' ] );
		}
	}

	/**
	 * Form inputs.
	 */
	protected function getInputFields(): array
	{
		global $current_user;

		/* @formatter:off */
		$fields = [
			'from' => [
				'type'     => 'text',
				'title'    => 'From',
				'class'    => 'regular-text',
				'defval'   => sprintf( '%s <%s>', get_option( 'blogname' ), get_option( 'admin_email' ) ),
			],
			'to' => [
				'type'     => 'text',
				'title'    => 'To',
				'class'    => 'regular-text',
				'defval'   => sprintf( '%s <%s>', $current_user->display_name, $current_user->user_email ),
			],
			'subject' => [
				'type'     => 'text',
				'title'    => 'Subject',
				'class'    => 'regular-text',
				'defval'   => 'Subject string',
			],
			'message' => [
				'type'     => 'textarea',
				'class'    => 'large-text',
				'title'    => 'Message',
			],
		];
		/* @formatter:on */
		Input::fieldsPrepare( $fields );

		return $fields;
	}

	/**
	 * Feed Inputs with POST data.
	 */
	protected function getInputs(): Inputs
	{
		$post = $this->postData();
		$Inputs = new Inputs();

		foreach ( $this->getInputFields() as $field ) {
			$Inputs->add( new Input( $field, $post ) );
		}

		$Inputs->cfg( 'table', [ 'class' => 'form-table' ] );

		return $Inputs;
	}

	/**
	 * Tool: Submit.
	 *
	 * @link https://www.php.net/manual/en/mail.configuration.php
	 * @link https://developer.wordpress.org/reference/functions/wp_mail/
	 */
	public function actionSubmitMailer(): void
	{
		if ( !$this->Settings->formNonceCheck() ) {
			$this->Settings->adminNotice( 'Error', 'Sending mail failed!', [ 'split' => ' ' ] );
			return;
		}

		$Inputs = $this->getInputs();

		/* @formatter:off */
		$result = wp_mail(
			$Inputs->find('to')->val(),
			$Inputs->find('subject')->val(),
			$Inputs->find('message')->val(),
			[
				'From: ' . $Inputs->find('from')->val(),
			],
		);
		/* @formatter:on */

		[ $type, $msg ] = $result ? [ 'info', 'Sent' ] : [ 'error', 'Failed' ];
		$this->Settings->adminNotice( 'Mail', $msg, [ 'type' => $type ] );
	}

	/**
	 * Tool: Render.
	 */
	public function actionRenderMailer()
	{
		$Inputs = $this->getInputs();
		$Inputs->find( 'message' )->val( '' );

		$this->renderForm( $Inputs, [ 'button' => [ 'text' => 'Send' ] ] );
	}
}
