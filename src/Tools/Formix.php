<?php
/*
 * This file is part of the orkan/wp-base1 package.
 * Copyright (c) 2024 Orkan <orkans+wpbase1@gmail.com>
 */
namespace Orkan\WP\Base1\Tools;

use Orkan\Input;
use Orkan\Inputs;

/**
 * Settings tool: Formix.
 * Example of using cookies to store data between form submission.
 *
 * @author Orkan <orkans+wpbase1@gmail.com>
 */
class Formix extends \Orkan\WP\Base\Tools
{
	const TITLE = 'Formix';
	const NAME = 'formix';
	const ICON = 'forms';
	const MENU = true;

	/**
	 * Does input fields require reload?
	 * After submit the cookie inputs[defval] array is updated!
	 */
	protected $isDirty = true;

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
		$this->Settings->cacheRegister( 'formix_inputs' );

		if ( $this->isPage ) {
			add_action( $this->Settings->getHookName( 'define' ), [ $this, 'actionDefineFormix' ] );
			add_action( $this->Settings->getHookName( 'render' ), [ $this, 'actionRenderFormix' ] );
			add_action( $this->Settings->getHookName( 'submit' ), [ $this, 'actionSubmitFormix' ] );
		}

		$this->ready = true;
	}

	/**
	 * Tool: Define.
	 */
	public function actionDefineFormix(): void
	{
		foreach ( Input::fieldPluck( 'enqueue', $this->getInputFields() ) as $handle ) {
			wp_enqueue_script( $handle );
		}

		$this->Plugin->enqueue( 'css/tool-formix.css' );
	}

	/**
	 * Tool: Submit.
	 */
	public function actionSubmitFormix(): void
	{
		$this->cookieSave( $this->getInputFields() );

		// COOKIE data updated, reload inputs!
		$this->isDirty = true;
	}

	/**
	 * Mix inputs with Settings page Section B inputs.
	 */
	protected function getInputFields(): array
	{
		$fields = $this->Settings->cacheGet( 'formix_inputs' );

		if ( $fields && !$this->isDirty ) {
			return $fields;
		}

		$fields = parent::getInputFields();

		// Import fields from Plugin > Settings: Section B
		$pluFields = array_filter( $this->Settings->getInputFields(), function ( $v ) {
			return 'section_b' === $v['section'];
		} );
		$settings = &$fields['settings']['items']['table']['items']; // shortcut
		$settings = array_merge( $settings, $pluFields );
		Input::fieldsPrepare( $settings );

		// Set values
		foreach ( Input::fieldsEach( $fields, true ) as $name => &$field ) {
			$val1 = $_COOKIE[static::NAME][$name] ?? null;
			$val2 = $this->Settings->getOption( $name, true, null );
			$val3 = $field['defval'] ?? null;

			$field['defval'] = $val1 ?? $val2 ?? $val3;
		}

		$this->Settings->cacheSet( 'formix_inputs', $fields );
		$this->isDirty = false;

		return $fields;
	}

	/**
	 * Tool: Render.
	 */
	public function actionRenderFormix(): void
	{
		$Table = new Inputs();

		foreach ( $this->getInputFields() as $field ) {
			$Table->add( new Input( $field ) );
		}

		try {
			// Get all Inputs from Table (flattened)
			$Inputs = Input::inputsAll( $Table->elements( true ), true );

			// Show admin notice?
			if ( $notice = $Inputs['notice']->val() ) {
				$this->Settings->adminNotice( $Inputs['notice']->get( 'title' ), $notice );
			}

			// Update: Formix > Notes
			$notes = strtr( $Inputs['notes']->val(), [ '{text_b1}' => $Inputs['text_b1']->val() ] );
			$Inputs['notes']->cfg( 'html', $notes );

			// Show messages if any...
			echo $this->Settings->adminNotices();
		}

		catch ( \Exception $E ) {
			echo $E->getMessage();
		}

		// The FORM
		$this->renderForm( $Table, [ 'button' => [ 'text' => 'Save data' ] ] );
	}
}
