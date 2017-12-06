<?php

namespace WPEmergeTwig\Templating;

use WPEmerge\ServiceProviders\ServiceProviderInterface;
use Twig_Environment;
use Twig_Loader_Filesystem;

class ServiceProvider implements ServiceProviderInterface {
	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		$container['wp_emerge_twig.templating.engine'] = function( $c ) {
			$key = 'wp_emerge_twig.templating.engine.options';
			$options = isset( $c[ $key ] ) ? $c[ $key ] : [];

			$options = array_merge( [
				'views' => get_stylesheet_directory(),
				'twig' => [
					'cache' => get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'twig',
				]
			], $options );

			$loader = new Twig_Loader_Filesystem( $options['views'] );
			$twig = new Twig_Environment( $loader, $options['twig'] );
			return new Engine( $twig, $options['views'] );
		};
	}

	/**
	 * {@inheritDoc}
	 */
	public function boot( $container ) {
		// nothing to boot
	}
}
