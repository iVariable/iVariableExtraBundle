<?php

namespace iVariable\ExtraBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class iVariableExtraExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
		//var_dump($container->getParameter( 'ivariable.extra.repo.class'), $container);die();
		if( !empty( $config['repo'] ) ){

			//Main Repo
			$container->setDefinition(
				'iv.repo',
				new \Symfony\Component\DependencyInjection\Definition(
					$container->getParameter( 'ivariable.extra.repo.class'),
					array(
						'em'	=> new \Symfony\Component\DependencyInjection\Reference('em'),
						'map'	=> $config['repo'],
					)
				)
			);

			foreach( $config['repo'] as $key => $options ){
				$definition = new \Symfony\Component\DependencyInjection\Definition(
					$container->getParameter( 'ivariable.extra.repo.class'),
					array(
						$key
					)
				);
				$definition->setFactoryService('iv.repo');
				$definition->setFactoryMethod('get');
				$container->setDefinition(
					'iv.repo.'.$key,
					$definition
				);
			}
		}
    }
}
