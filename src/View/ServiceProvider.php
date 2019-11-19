<?php

namespace WPEmergeTwig\View;

use Twig_Environment;
use Twig_Function;
use Twig_Loader_Filesystem;
use WPEmerge\Helpers\MixedType;
use WPEmerge\ServiceProviders\ExtendsConfigTrait;
use WPEmerge\ServiceProviders\ServiceProviderInterface;
use WPEmerge\View\NameProxyViewEngine;

class ServiceProvider implements ServiceProviderInterface {
	use ExtendsConfigTrait;

	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		$cache_dir = $container[ WPEMERGE_CONFIG_KEY ]['cache']['path'];

		$this->extendConfig( $container, 'twig', [
			'replace_default_engine' => true,
			'proxy_php_views' => true,
			'views' => [get_stylesheet_directory(), get_template_directory()],
			'options' => [
				'debug' => $container[ WPEMERGE_CONFIG_KEY ]['debug']['enable'],
				'base_template_class' => Template::class,
				'cache' => MixedType::addTrailingSlash( $cache_dir ) . 'twig',
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

			$compose = new Twig_Function('wpemerge_compose', function ( $view ) use ( $c ) {
				$c[ WPEMERGE_VIEW_COMPOSE_ACTION_KEY ]( $view );
			});

			$twig->addFunction( $compose );

			return new ViewEngine( $loader, $twig, $views );
		};

		if ( $container[ WPEMERGE_CONFIG_KEY ]['twig']['replace_default_engine'] ) {
			$container[ WPEMERGE_VIEW_ENGINE_KEY ] = function( $c ) {
				if ( $c[ WPEMERGE_CONFIG_KEY ]['twig']['proxy_php_views'] ) {
					return new NameProxyViewEngine(
						$c[ WPEMERGE_APPLICATION_KEY ],
						[
							'.twig.php' => WPEMERGETWIG_VIEW_TWIG_VIEW_ENGINE_KEY,
							'.php' => WPEMERGE_VIEW_PHP_VIEW_ENGINE_KEY,
						],
						WPEMERGETWIG_VIEW_TWIG_VIEW_ENGINE_KEY
					);
				}

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
