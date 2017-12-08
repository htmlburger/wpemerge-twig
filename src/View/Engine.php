<?php

namespace WPEmergeTwig\View;

use WPEmerge\View\EngineInterface;
use Twig_Environment;

class Engine implements EngineInterface {
	/**
	 * Twig instance
	 *
	 * @var \Twig_Environment
	 */
	protected $twig = null;

	/**
	 * Root directory for all views
	 *
	 * @var string
	 */
	protected $views = '';

	/**
	 * Constructor
	 *
	 * @param \Twig_Environment $twig
	 * @param array             $global_context
	 * @param string            $views
	 */
	public function __construct( Twig_Environment $twig, $global_context, $views ) {
		$this->twig = $twig;
		$this->views = $views;

		$this->twig->addGlobal( 'global', $global_context );
	}

	/**
	 * {@inheritDoc}
	 */
	public function render( $file, $context ) {
		$view = $this->twig->load( substr( $file, strlen( $this->views ) ) );
		return $view->render( $context );
	}

	/**
	 * Get the Twig_Environment instance
	 *
	 * @return \Twig_Environment
	 */
	public function environment() {
		return $this->twig;
	}
}
