<?php

namespace WPEmergeTwig\View;

use Exception;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;
use Twig_TemplateWrapper;
use WPEmerge\Facades\View;
use WPEmerge\View\HasContextTrait;
use WPEmerge\View\HasNameTrait;
use WPEmerge\View\ViewInterface;

/**
 * Render a view file with php.
 */
class TwigView implements ViewInterface {
	use HasContextTrait, HasNameTrait;

	/**
	 * Twig view.
	 *
	 * @var Twig_TemplateWrapper
	 */
	protected $twig_view = null;

	/**
	 * {@inheritDoc}
	 */
	public function getTwigView() {
		return $this->twig_view;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setTwigView( Twig_TemplateWrapper $twig_view ) {
		$this->twig_view = $twig_view;
		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function toString() {
		if ( empty( $this->getName() ) ) {
			throw new Exception( 'View must have a name.' );
		}

		$context = $this->getContext();
		return $this->getTwigView()->render( $context );
	}

	/**
	 * {@inheritDoc}
	 */
	public function toResponse() {
		return (new Response())
			->withHeader( 'Content-Type', 'text/html' )
			->withBody( Psr7\stream_for( $this->toString() ) );
	}
}
