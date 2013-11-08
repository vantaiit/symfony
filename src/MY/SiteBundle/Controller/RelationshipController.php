<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 8/22/13
 * Time: 5:27 PM
 * To change this template use File | Settings | File Templates.
 */

namespace MY\SiteBundle\Controller;

use MY\EntityBundle\Entity\Relationships;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/relationship")
 */
class RelationshipController extends Controller
{

    /**
     * @Route("/friend/request", name="_request_friend")
    */
    public function requestFriendAction(Request $request)
    {

        $id = $request->request->get('id');
        $status = false;
        if($id == $this->getUser()->getId()) {
            return new JsonResponse(array('status' => $status));
        }

        /* @var \Doctrine\ORM\EntityManager $em*/
        $em = $this->getDoctrine()->getManager();
        $friend = $em->getRepository('MYEntityBundle:User')->find($id);

        if($friend) {
            $status = true;
            $entity = $em->getRepository('MYEntityBundle:Relationships')->findOneBy(
                array('user_id' => $this->getUser()->getId(), 'friend_user_id' => $id)
            );

            if (!$entity) {
                $entity = new Relationships();
                $entity->setFriendUserId($friend);
                $entity->setUserId($this->getUser());
                $entity->setStatus(Relationships::REQUEST_FRIEND);
                $em->persist($entity);
                $em->flush();
            }

        }

        return new JsonResponse(array('status' => $status));
    }

    /**
     * @Route("/friend/request/accept", name="_accept_friend_request")
     * @Method("POST")
     */
    public function acceptFriendRequestAction(Request $request)
    {
        $id = $request->request->get('id');
        $status = false;
        $user = $this->getUser();
        /* @var \Doctrine\ORM\EntityManager $em*/
        $em = $this->getDoctrine()->getManager();
        $friend = $em->getRepository('MYEntityBundle:User')->find($id);
        if($friend) {
            $entity = $em->getRepository('MYEntityBundle:Relationships')->findOneBy(array('user_id' => $id, 'friend_user_id' => $user->getId()));
            if($entity) {
                $entity->setStatus(Relationships::FRIEND);
                $user->setCountFriend($user->getCountFriend()+1);
                $entity2 = $em->getRepository('MYEntityBundle:Relationships')->findOneBy(array('user_id' => $user->getId(), 'friend_user_id' => $id));
                if (!$entity2) {
                    $entity2 = new Relationships();
                    $entity2->setFriendUserId($friend);
                    $friend->setCountFriend($friend->getCountFriend()+1);
                    $entity2->setUserId($user);
                }
                $entity2->setStatus(Relationships::FRIEND);
                $em->persist($user);
                $em->persist($friend);
                $em->persist($entity);
                $em->persist($entity2);
                $em->flush();
                $status = true;
            }
        }

        return new JsonResponse(array('status' => $status));
    }

    /**
     * @Route("/friend/request/cancel", name="_cancel_friend_request")
     * @Method("POST")
     */
    public function cancelFriendRequestAction(Request $request)
    {
        $id = $request->request->get('id');
        $status = false;

        /* @var \Doctrine\ORM\EntityManager $em*/
        $em = $this->getDoctrine()->getManager();
        $friend = $em->getRepository('MYEntityBundle:User')->find($id);
        if ($friend) {
            $entity1 = $em->getRepository('MYEntityBundle:Relationships')->findOneBy(array('user_id' => $id, 'friend_user_id' => $this->getUser()->getId()));
            $entity2 = $em->getRepository('MYEntityBundle:Relationships')->findOneBy(array('user_id' => $this->getUser()->getId(), 'friend_user_id' =>$id ));
            if($entity1) {
//                $entity->setStatus(Relationships::REQUEST_CANCEL);
                $em->remove($entity1);
                if($entity2)
                    $em->remove($entity2);
                $em->flush();
                $status = true;
            }
        }

        return new JsonResponse(array('status' => $status));
    }

}
