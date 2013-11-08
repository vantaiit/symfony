<?php

namespace MY\SiteBundle\Controller;

use MY\EntityBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilder;
use MY\SiteBundle\Form\Type\ProfileType;

/**
 * @Route("profile")
*/
class ProfileController extends BaseController
{
    /**
     * @Route("/", name="_user_profile")
     * @Template()
     */
    public function profileAction($id = null)
    {
        /* @var \Doctrine\ORM\EntityManager $em*/
        $em = $this->getDoctrine()->getManager();
        $isPublic = true;
        $isFriend = false;
//        $user = $this->getUser();
        if(!$id) {
            $isPublic = false;
            $id = $this->getUser()->getId();
        }else {
            if($this->getUser() instanceof UserInterface && $this->getUser()->getId() == $id) {
                $isPublic = false;
            }
        }

        $user = $em->getRepository('MYEntityBundle:User')->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Unable to find user.');
        }
        $utility = $this->get('utility');

        return array('owner_id' => $id, 'user' => $user);
    }

    /**
     * @Route("/intro", name="_user_intro")
     * @Template()
    */
    public function introAction()
    {
        return array();
    }

    /**
     * @Route("/edit", name="_user_profile_edit")
     * @Template()
     */
    public function updateAction(Request $request)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new ProfileType(), $user);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $em->persist($user);
                $em->flush();
            }
        }
        return array(
            'form'=> $form->createView()
        );
    }

}
