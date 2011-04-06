<?php

namespace CodeMeme\AdminBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class CodeMemeAdminExtension extends Extension
{

    public function load(Array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        foreach ($configs as $config) {
            if (isset($config['models'])) {
                $this->loadModels($config['models'], $container);
            }
        }
    }

    protected function loadModels($models, ContainerBuilder $container)
    {
        $collection = $container->get('admin.models');
        $class      = $container->getParameter('admin.model_container.class');
        
        foreach ($models as $slug => $config) {
            $modelContainer = new $class;
            
            $modelContainer->setClass($config['class']);
            $modelContainer->setContainer($container);
            
            $collection->set($slug, $modelContainer);
            $container->set(sprintf('admin.%s_model', $slug), $modelContainer);
        }
    }

    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }

    public function getNamespace()
    {
        return 'http://codememe.org/schema/dic/admin';
    }

}