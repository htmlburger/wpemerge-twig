<?php

namespace WPEmergeTwig\View;

use WPEmerge\ServiceProviders\ServiceProviderInterface;
use Twig_Environment;
use Twig_Loader_Filesystem;

class ServiceProvider implements ServiceProviderInterface {
	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		$container['wpemerge_twig.view.viewengine'] = function( $c ) {
			$key = 'wpemerge_twig.view.viewengine.options';
			$options = isset( $c[ $key ] ) ? $c[ $key ] : [];

			$options = array_merge( [
				'views' => get_stylesheet_directory(),
				'twig' => [
					'base_template_class' => Template::class,
					'cache' => get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'twig',
				]
			], $options );

			$loader = new Twig_Loader_Filesystem( $options['views'] );
			$twig = new Twig_Environment( $loader, $options['twig'] );
			return new ViewEngine( $loader, $twig, $options['views'] );
		};
	}

	/**
	 * {@inheritDoc}
	 */
	public function boot( $container ) {
		// nothing to boot
	}
}
