<?php

namespace MY\SiteBundle\Controller;

use MY\EntityBundle\Entity\Idea;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/idea")
*/
class IdeaController extends Controller
{

    /**
     * @Route("/create")
    */
    public function createAction(Request $request)
    {
        if($request->isMethod('POST')){

            return new JsonResponse(array('status'=>true));
        }
        return new JsonResponse(array('status'=>false));

    }
}
