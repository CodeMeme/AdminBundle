<?php

namespace CodeMeme\AdminBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
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
        
        foreach ($configs as $config) {
            if (isset($config['groups'])) {
                $this->loadGroups($config['groups'], $container);
            }
        }
    }

    protected function loadGroups($groups, ContainerBuilder $container)
    {
        $containerDef = new Definition('%admin.group_collection.class%');
        
        foreach ($groups as $key => $config) {
            $groupDef = new Definition("%admin.group_container.class%");
            $groupKey = sprintf('admin.%s_group', $key);
            
            $containerDef->addMethodCall('set', array($key, $groupDef));
            $container->setDefinition($groupKey, $groupDef);
            
            $config = array_merge(array(
                'label' => ucwords(str_replace(array('-', '_'), ' ', $key)),
            ), $config);
            
            $setters = array(
                'setLabel' => $config['label'],
            );
            
            foreach ($setters as $setter => $value) {
                $groupDef->addMethodCall($setter, array($value));
            }
            
            if (isset($config['models'])) {
                foreach ($config['models'] as $model) {
                    $modelKey = sprintf('admin.%s_model', $model);
                    $modelDef = $container->getDefinition($modelKey);
                    
                    $modelDef->addMethodCall('setGroup', array(new Reference($groupKey)));
                    $groupDef->addMethodCall('addModel', array(new Reference($modelKey)));
                }
            }
        }
        
        $container->setDefinition('admin.group_collection', $containerDef);
        $container->setAlias('admin.groups', 'admin.group_collection');
    }

    protected function loadModels($models, ContainerBuilder $container)
    {
        $containerDef = new Definition('%admin.model_collection.class%');
        
        foreach ($models as $key => $config) {
            $modelDef = new Definition('%admin.model_container.class%');
            
            $config = array_merge(array(
                'label'         =>  ucwords(str_replace(array('_', '-'), ' ', $key)),
                'slug'          =>  $key,
                'entityManager' =>  'default',
            ), $config);
            
            $setters = array(
                'setContainer'      =>  new Reference('service_container'),
                'setClass'          =>  $config['class'],
                'setSlug'           =>  $config['slug'],
                'setLabel'          =>  $config['label'],
                'setEntityManager'  =>  new Reference(sprintf('doctrine.orm.%s_entity_manager', $config['entityManager'])),
            );
            
            foreach ($setters as $setter => $value) {
                $modelDef->addMethodCall($setter, array($value));
            }
            
            $containerDef->addMethodCall('set', array($key, $modelDef));
            
            $container->setDefinition(sprintf('admin.%s_model', $key), $modelDef);
        }
        
        $container->setDefinition('admin.model_collection', $containerDef);
        $container->setAlias('admin.models', 'admin.model_collection');
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