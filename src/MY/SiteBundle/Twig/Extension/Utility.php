<?php
/**
 * @author Viet Pham
 */
namespace MY\SiteBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Utility extends \Twig_Extension implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     *
     * @api
     */
    protected $container;

    /**
     * Sets the Container associated with application.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('hashUrl', array($this, 'generateHashUrl')),
        );
    }

    public function generateHashUrl($path)
    {
        $basePath = $this->container->get('request')->getBasePath();
        return $basePath . '#' . (substr($path, 0, 1) == '/' ? $path : '/' . $path);
    }

    public function getName()
    {
        return 'acme_extension';
    }
}