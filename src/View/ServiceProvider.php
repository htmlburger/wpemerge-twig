<?php

namespace WPEmergeTwig\View;

use WPEmerge\Helpers\MixedType;
use WPEmerge\ServiceProviders\ExtendsConfigTrait;
use WPEmerge\ServiceProviders\ServiceProviderInterface;
use Twig_Environment;
use Twig_Loader_Filesystem;

class ServiceProvider implements ServiceProviderInterface {
	use ExtendsConfigTrait;

	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		$this->extendConfig( $container, 'twig', [
			'replace_default_engine' => true,
			'views' => [get_stylesheet_directory(), get_template_directory()],
			'options' => [
				'base_template_class' => Template::class,
				'cache' => MixedType::normalizePath( get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'twig' ),
			],
		] );

		$container[ WPEMERGETWIG_VIEW_TWIG_VIEW_ENGINE_KEY ] = function( $c ) {
			$root = MixedType::normalizePath( ABSPATH );

			$config = $c[ WPEMERGE_CONFIG_KEY ]['twig'];
			$views = MixedType::toArray( $config['views'] );
			$views = array_map( [MixedType::class, 'normalizePath'], $views );
			$views = array_filter( $views );
			$relative_views = array_map( function ( $directory ) use ( $root ) {
				return substr( $directory, strlen( $root ) );
			}, $views );

			$loader = new Twig_Loader_Filesystem( $relative_views, $root );
			$twig = new Twig_Environment( $loader, $config['options'] );
			return new ViewEngine( $loader, $twig, $views );
		};

		if ( $container[ WPEMERGE_CONFIG_KEY ]['twig']['replace_default_engine'] ) {
			$container[ WPEMERGE_VIEW_ENGINE_KEY ] = function( $c ) {
				return $c[ WPEMERGETWIG_VIEW_TWIG_VIEW_ENGINE_KEY ];
			};
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( $container ) {
		// nothing to boot
	}
}
