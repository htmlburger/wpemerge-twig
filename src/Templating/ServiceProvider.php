<?php

namespace ObsidianTwig\Templating;

use Obsidian\ServiceProviders\ServiceProviderInterface;
use Twig_Environment;
use Twig_Loader_Filesystem;

class ServiceProvider implements ServiceProviderInterface {
	/**
	 * Register all dependencies in the IoC container
	 *
	 * @param  \Pimple\Container $container
	 * @return null
	 */
	public function register( $container ) {
		$container['obsidian_twig.templating.engine'] = function( $c ) {
			$key = 'obsidian_twig.templating.engine.options';
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
	 * Bootstrap any services if needed
	 *
	 * @param  \Pimple\Container $container
	 * @return null
	 */
	public function boot( $container ) {
		// nothing to boot
	}
}