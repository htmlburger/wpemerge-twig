<?php

namespace WPEmergeTwig\View;

use View;
use Twig_Environment;
use Twig_ExistsLoaderInterface;
use WPEmerge\View\EngineInterface;
use WPEmerge\View\GlobalAdded;

class Engine implements EngineInterface {
	/**
	 * Twig loader
	 *
	 * @var Twig_ExistsLoaderInterface
	 */
	protected $loader = null;

	/**
	 * Twig instance
	 *
	 * @var Twig_Environment
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
	 * @param Twig_ExistsLoaderInterface $loader
	 * @param Twig_Environment           $twig
	 * @param string                     $views
	 */
	public function __construct( Twig_ExistsLoaderInterface $loader, Twig_Environment $twig, $views ) {
		$this->loader = $loader;
		$this->twig = $twig;
		$this->views = $views;

		$this->environment()->addGlobal( 'global', View::getGlobals() );
	}

	/**
	 * {@inheritDoc}
	 */
	public function exists( $view ) {
		return $this->loader()->exists( $view );
	}

	/**
	 * {@inheritDoc}
	 */
	public function canonical( $view ) {
		// ::findTemplate() is private so we use a suitable alternative
		return $this->loader->getCacheKey( $view );
	}

	/**
	 * {@inheritDoc}
	 */
	public function render( $views, $context ) {
		foreach ( $views as $view ) {
			if ( $this->exists( $view ) ) {
				return $this->environment()->load( $view )->render( $context );
			}
		}

		return '';
	}

	/**
	 * Get the Twig_Environment instance
	 *
	 * @return Twig_Environment
	 */
	public function environment() {
		return $this->twig;
	}

	/**
	 * Get the Twig_ExistsLoaderInterface instance
	 *
	 * @return Twig_ExistsLoaderInterface
	 */
	public function loader() {
		return $this->loader;
	}
}
