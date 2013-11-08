<?php

namespace MY\SiteBundle\Controller;

use MY\EntityBundle\Entity\Category;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ___PHPSTORM_HELPERS\object;

/**
 * @Route("/category" )
 */
class CategoryController extends Controller
{
    /**
     * @Route("/getmosttopic", name="_category_most_topic")
    */
    Public function getTopicAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("MYEntityBundle:Category")->getMostTopic();
        $strMostTopic = '';
        foreach($entities as $entity){
            $strMostTopic .= '<li data-id="'.$entity['id'].'"><a href="javascript:void(0);" title=""><i></i><span class="text">'.$entity['name'].'</span></a></li>';
        }
        return new JsonResponse(array('status'=>true,'html'=>$strMostTopic));
    }

    /**
     * @Route("/besttopic", name="_category_best_topic")\
     * @Template()
    */
    Public function getBestTopicAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("MYEntityBundle:Category")->bestTopic();
        $userfollowtopic = $em->getRepository("MYEntityBundle:UserFollowTopic")->isFollowTopic(array('topic'=>$entities));
        return array('entities'=>$entities);
    }
    
    /**
     * @Route("/create", name="_category_create")
     * @Template()
    */
    public function createAction(Request $request){
        // get doctrine manager.
        $em = $this->getDoctrine()->getManager();
        // get form.
        $form = $this->_getForm();
        // bind request to form.
        $form->handleRequest($request);
        // check if the form is valid.
        if ($form->isValid()) {
            // get form data.
            $data = $form->getData();
            // get current user.
            $data['user'] = $this->getUser();
            // extra data into category entity.
            $entity = new Category($data);
            // push into database.
            $em->persist($entity);
            $em->flush();
            // set flash success
            $this->get('session')->getFlashBag()->add('success', "Save topic is successfull!");
        }
        
        return $this->render(
            'MYSiteBundle:Category:create.html.twig',
            array('form'=> $form->createView())
        );
    }
    
    /**
     * get category form.
     * @param array $defaultData
     * @return form
     */
    private function _getForm($defaultData = array()){
        $form = $this->createFormBuilder($defaultData, array('csrf_protection' => false, 'trim' => true));
        $form->add('name', 'text', array(
            'constraints' => new NotBlank(),
        ));
        $form->add('is_topic', 'hidden', array(
            'constraints' => new NotBlank(),
            'required' => false,
            'data' => 1
        ));
        $form->add('description', 'textarea',
                array(
                    'constraints' => new NotBlank(),
                    'required' => false,
                    'attr' => array(
                            'rows' => 5
                        )
                    )
                );

        return $form->getForm();
    }
}
