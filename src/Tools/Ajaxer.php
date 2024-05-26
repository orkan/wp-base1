<?php
/*
 * This file is part of the orkan/wp-base1 package.
 * Copyright (c) 2024 Orkan <orkans+wpbase1@gmail.com>
 */
namespace Orkan\WP\Base1\Tools;

use Orkan\Input;
use Orkan\Inputs;

/**
 * Settings tool: Ajaxer.
 * Example of using Ajax within plugin Tool page.
 *
 * @author Orkan <orkans+wpbase1@gmail.com>
 */
class Ajaxer extends \Orkan\WP\Base\Tools
{
	const TITLE = 'Ajaxer';
	const NAME = 'ajaxer';
	const ICON = 'update';
	const MENU = true;

	/* @formatter:off */

	/**
	 * Registered Ajax actions.
	 * @var array (
	 *   [key] => "action" used in add_action() as "wp_ajax_{action}" -or- "wp_ajax_nopriv_{action}"
	 *   ...
	 * )
	 */
	protected $ajaxActions = [
		'selector'   => 'ork_ajaxer_selector',
		'echotext'   => 'ork_ajaxer_echotext',
		'flushcache' => 'ork_ajaxer_cc',
	];

	/* @formatter:on */

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
		$this->Settings->cacheRegister( 'ajaxer_inputs', 'ajaxer_meta_fields' );

		if ( $this->isAjax && in_array( $_REQUEST['action'], $this->ajaxActions ) ) {
			$this->Factory->Plugin()->ajaxExceptionHandle();
			add_action( 'wp_ajax_' . $this->ajaxActions['selector'], [ $this, 'actionAjaxSelector' ] );
			add_action( 'wp_ajax_' . $this->ajaxActions['echotext'], [ $this, 'actionAjaxTexter' ] );
			add_action( 'wp_ajax_' . $this->ajaxActions['flushcache'], [ $this, 'actionAjaxFlushCache' ] );
		}
		elseif ( $this->isPage ) {
			add_action( $this->Settings->getHookName( 'define' ), [ $this, 'actionDefineAjaxer' ] );
			add_action( $this->Settings->getHookName( 'render' ), [ $this, 'actionRenderAjaxer' ] );
		}

		$this->ready = true;
	}

	/**
	 * Ajax request: GET.
	 */
	public function actionAjaxFlushCache()
	{
		$this->Plugin->ajaxNonceCheck();

		if ( !$this->Settings->cacheFlush( 'ajaxer_inputs' ) ) {
			$this->Settings->adminNotice( static::TITLE, '0 items deleted.' );
		}

		wp_send_json_success( $this->Settings->adminNotices() );
	}

	/**
	 * Ajax request: POST.
	 */
	public function actionAjaxSelector()
	{
		$this->Plugin->ajaxNonceCheck();

		$name = $_REQUEST['name'] ?? null;
		$value = $this->Factory->get( $name, 'NULL' );

		// Add pause to test Ajax race conditions. See: tool-ajaxer.js
		usleep( rand( 0, 2000000 ) );

		wp_send_json_success( "name: $name, value: $value" );
	}

	/**
	 * Ajax request: POST.
	 */
	public function actionAjaxTexter()
	{
		$this->Plugin->ajaxNonceCheck();

		$text = $_REQUEST['text'] ?? null;
		$error = $_REQUEST['error'] ?? null;

		if ( Input::filterCheckbox( $error ) ) {
			throw new \RuntimeException( $text );
		}

		// Add pause to test Ajax race conditions. See: tool-ajaxer.js
		usleep( rand( 0, 2000000 ) );

		wp_send_json_success( "text: $text" ); // + die()
		wp_send_json_error( "WP error in " . __METHOD__ ); // + die()
	}

	/**
	 * Tool: Define.
	 */
	public function actionDefineAjaxer(): void
	{
		$fields = $this->getInputFields();

		// Javascript: window.ork = {...}
		/* @formatter:off */
		$jsData = [
			'actions' => [
				'ajaxer' => $this->ajaxActions,
			],
			'l10n' => [
				'wait' => 'Wait for response...',
			],
		];
		/* @formatter:on */

		foreach ( Input::fieldsEach( $fields ) as $name => $input ) {
			$jsData['name2id'][$name] = Input::buildId( $input['name'] );
		}

		$this->Plugin->enqueue( 'css/tool-ajaxer.css', [ 'forms' ] );
		$this->Plugin->enqueue( 'js/tool-ajaxer.js', [ 'data' => $this->Settings->jsObject( $jsData ) ] );

		//wp_enqueue_style( 'ork-bootstrap', '/' . $this->Factory->get( 'assets_loc' ) . '/bootstrap/bootstrap.min.css' );
	}

	/**
	 * Get inputs.
	 */
	protected function getInputFields(): array
	{
		$fields = $this->Settings->cacheGet( 'ajaxer_inputs' );

		if ( $fields ) {
			return $fields;
		}

		$fields = parent::getInputFields();

		foreach ( array_keys( $this->Factory->cfg() ) as $k ) {
			$fields['selector']['items']['selector_select']['items'][$k] = "cfg[$k]";
		}

		$this->Settings->cacheSet( 'ajaxer_inputs', $fields );

		return $fields;
	}

	/**
	 * Tool: Render.
	 */
	public function actionRenderAjaxer(): void
	{
		$Inputs = new Inputs( [], [ 'element' => 'div' ] );

		foreach ( $this->getInputFields() as $field ) {
			$Inputs->add( new Input( $field ) );
		}

		$Inputs->renderParagraphs();
	}
}
