<?php

namespace ObsidianTwig\Templating;

use Obsidian\ServiceProviders\ServiceProviderInterface;

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
			return Engine::make( $options );
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
