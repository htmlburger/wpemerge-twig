<?php

namespace ObsidianTwig\Templating;

use Obsidian\Templating\EngineInterface;
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
	 * @param string            $views
	 */
	public function __construct( Twig_Environment $twig, $views ) {
		$this->twig = $twig;
		$this->views = $views;
	}

	/**
	 * Render a template to a string
	 *
	 * @param  string $file
	 * @param  array  $context
	 * @return string
	 */
	public function render( $file, $context ) {
		$template = $this->twig->load( substr( $file, strlen( $this->views ) ) );
		return $template->render( $context );
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
