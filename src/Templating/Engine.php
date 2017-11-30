<?php

namespace ObsidianTwig\Templating;

use Twig_Environment;
use Twig_Loader_Filesystem;
use Obsidian\Templating\EngineInterface;

class Engine implements EngineInterface {
	/**
	 * Twig instance
	 *
	 * @var \Twig_Environment
	 */
	protected $twig = null;

	/**
	 * Constructor
	 *
	 * @param \Twig_Environment $twig
	 */
    public function __construct( Twig_Environment $twig ) {
        $this->twig = $twig;
    }

	/**
	 * Render a template to a string
	 *
	 * @param  string $file
	 * @param  array  $context
	 * @return string
	 */
	public function render( $file, $context ) {
		$template = $this->twig->load( substr( $file, strlen( ABSPATH ) ) );
        return $template->render( $context );
	}

	/**
	 * Creates a new instance
	 *
	 * @param  array  $options
	 * @return Engine
	 */
	public static function make( $options ) {
		$options = array_merge( [
			'cache' => get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'twig',
		], $options );

		$loader = new Twig_Loader_Filesystem( ABSPATH );
		$twig = new Twig_Environment( $loader, $options );
		return new static( $twig );
	}
}
