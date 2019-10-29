<?php

namespace WPEmergeTwig\View;

use Twig_Template;

abstract class Template extends Twig_Template {
	/**
	 * {@inheritDoc}
	 */
	public function display( array $context, array $blocks = [] ) {
		$compose = $this->env->getFunction( 'wpemerge_compose' );

		$view = (new TwigView())->setName( $this->getTemplateName() );

		$function = $compose->getCallable();
		$function( $view );

		$context = array_merge(
			$view->getContext(),
			$context
		);

		parent::display( $context, $blocks );
	}
}
