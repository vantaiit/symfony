<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 9/5/13
 * Time: 10:24 AM
 * To change this template use File | Settings | File Templates.
 */

namespace MY\SiteBundle\Controller;

use MY\EntityBundle\Entity\UserFollowTopic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("userfollowtopic")
 */

class UserFollowTopicController extends BaseController
{

    /**
     * @Route("/", name="userfollowtopic")
     * @Template("MYSiteBundle:UserFollowTopic:index.html.twig")
    */
    public function indexAction(Request $request)
    {
        return array('status'=>false);
    }

    /**
     * @Route("/add", name="userfollowTopic_add")
     * @Method("POST")
    */
    public function addLikeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new UserFollowTopic();
        $topic = $em->getRepository("MYEntityBundle:Category")->find($request->request->get('topicId'));
        if($topic)
            try{
                $entity->setTopic($topic);
                $entity->setUser($this->getUser());
                $em->persist($entity);
                $em->flush();
                return new JsonResponse(array('status'=>true));
            }catch (\Exception $ex)
            {
                return new JsonResponse(array('status'=>false,'topicId'=>$request->request->get('topicId')));
            }
        return new JsonResponse(array('status'=>false,'topicId'=>false));
    }
}
