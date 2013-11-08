<?php

namespace MY\SiteBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MYSiteBundle extends Bundle {

    private static $containerInstance = null;

    public function setContainer(\Symfony\Component\DependencyInjection \ContainerInterface $container = null) {
        parent::setContainer($container);
        self::$containerInstance = $container;
    }

    /**
     * get application container
     * @see \Symfony\Component\DependencyInjection \ContainerInterface 
     */
    public static function getContainer() {
        return self::$containerInstance;
    }
    
    /**
     * get root directory
     * @return type
     */
    public static function getRootDir(){
        return realpath(dirname(__FILE__).'/../../../');
        
    }
    
    /**
    * get web directory
    * 
    */
    public static function getRootWebDir(){
        return self::getRootDir() . DIRECTORY_SEPARATOR . 'web';
        
    }

}
