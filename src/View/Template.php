<?php

namespace WPEmergeTwig\View;

use Twig_Template;
use WPEmerge\Facades\View;

abstract class Template extends Twig_Template {
	/**
	 * {@inheritDoc}
	 */
	public function display( array $context, array $blocks = [] ) {
		$view = (new TwigView())->setName( $this->getTemplateName() );
		View::compose( $view );
		$context = array_merge(
			$view->getContext(),
			$context
		);
		parent::display( $context, $blocks );
	}
}
