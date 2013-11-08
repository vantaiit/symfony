<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 9/5/13
 * Time: 10:24 AM
 * To change this template use File | Settings | File Templates.
 */

namespace MY\SiteBundle\Controller;

use MY\EntityBundle\Entity\UserLikeIdea;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("userlikeidea")
 */

class UserLikeIdeaController extends BaseController
{

    /**
     * @Route("/", name="userlikeidea")
     * @Template("MYSiteBundle:UserLikeIdea:like.html.twig")
    */
    public function indexAction(Request $request)
    {
        return array('status'=>false);
    }

    /**
     * @Route("/add", name="userlikeidea_add")
     * @Method("POST")
    */
    public function addLikeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new UserLikeIdea();

        $idea = $em->getRepository("MYEntityBundle:Idea")->find($request->request->get('ideaId'));
        if($idea)
            try{
                $entity->setIdea($idea);
                $entity->setUser($this->getUser());
                $em->persist($entity);
                $em->flush();
                return new JsonResponse(array('status'=>true));
            }catch (\Exception $ex)
            {
                return new JsonResponse(array('status'=>false));
            }
        return new JsonResponse(array('status'=>false));
    }

}
