<?php

namespace WPEmergeTwig\View;

use Twig_Template;
use View;

abstract class Template extends Twig_Template {
	/**
	 * {@inheritDoc}
	 */
	public function display( array $context, array $blocks = [] ) {
		$view = preg_replace( '~^/~', '', $this->getTemplateName() );
		$context = array_merge(
			$context,
			View::compose( $view )
		);
		parent::display( $context, $blocks );
	}
}
