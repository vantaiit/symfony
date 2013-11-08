<?php
namespace MY\SiteBundle\Extension;

use Symfony\Component\DependencyInjection\Container;

use Symfony\Component\Locale\Locale;
class Utility extends \Twig_Extension {

    /**
     * @var Container $container
     */
    private $container;
    protected $countries = array();


    public function __construct( Container $container ) {
        $this->container = $container;
    }

    public function getContainer() {
        return $this->container;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),

        );
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }

    public function getFunctions()
    {
        return array(
            'truncate' => new \Twig_Function_Method($this, 'truncate')
        );
    }

    /**
     * Helper to use in controller
     */
    public function truncate($value, $length = 30, $preserve = false, $separator = '...')
    {
        $twig = $this->getContainer()->get('twig');
        return twig_truncate_filter($twig, $value, $length, $preserve, $separator);
    }



    public function getName()
    {
        return 'Utility';
    }
}
