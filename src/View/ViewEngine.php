<?php

namespace WPEmergeTwig\View;

use Twig_Environment;
use Twig_ExistsLoaderInterface;
use WPEmerge\Facades\View;
use WPEmerge\Helpers\Mixed;
use WPEmerge\View\ViewEngineInterface;

class ViewEngine implements ViewEngineInterface {
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
		$this->views = Mixed::normalizePath( realpath( $views ) );

		$this->environment()->addGlobal( 'global', View::getGlobals() );
	}

	/**
	 * {@inheritDoc}
	 */
	public function exists( $view ) {
		$view = $this->twigCanonical( $view );
		return $this->loader()->exists( $view );
	}

	/**
	 * {@inheritDoc}
	 */
	public function canonical( $view ) {
		$view = $this->twigCanonical( $view );
		// ::findTemplate() is private so we use a suitable alternative
		return $this->loader->getCacheKey( $view );
	}

	/**
	 * {@inheritDoc}
	 */
	public function make( $views ) {
		foreach ( $views as $view ) {
			$view = $this->twigCanonical( $view );
			if ( $this->exists( $view ) ) {
				return (new TwigView())
					->setName( $view )
					->setTwigView( $this->environment()->load( $view ) );
			}
		}

		throw new Exception( 'View not found for "' . implode( ', ', $views ) . '"' );
	}

	/**
	 * Return a canonical string representation of the view name in Twig's format.
	 *
	 * @param  string $view
	 * @return string
	 */
	public function twigCanonical( $view ) {
		$views_root = $this->views . DIRECTORY_SEPARATOR;
		$normalized = realpath( $view );

		if ( $normalized && is_file( $normalized ) ) {
			$view = preg_replace( '~^' . preg_quote( $views_root, '~' ) . '~', '', $normalized );
		}

		return $view;
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
