<?php

namespace IntrawayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IntrawayBundle\Entity as ibe;

class UserController extends Controller
{
    /**
     * @Route("/userProfile/new", name="newUser")
     */
    public function newAction()
    {
        $logger = $this->get('logger');
        $logger->error(sprintf('%s:%s %s',__CLASS__,__FUNCTION__,"NEW ACTION DOESNT WORK"));
        return $this->render('UserBundle:Default:new.html.twig', array());
    }
    
    /**
     * @Route("/userProfile/get/{user_id}", name="GetUser")
     */
    public function getAction($user_id = 0)
    {
        $user = new ibe\User();
        
        $user = $this->getDoctrine()
        ->getRepository('UserBundle:User')
        ->find($user_id);
        
        if (!$user) {
            throw $this->createNotFoundException(
                'No product found for id '.$user_id
                );
        }
        
        $logger = $this->get('logger');
        $logger->info(sprintf('%s:%s %s %s',__CLASS__,__FUNCTION__,'GET ACTION',$id));
        return $this->render('UserBundle:Default:index.html.twig');
    }
    
    /**
     * @Route("/userProfile/delete/{id}", name="DeleteUser")
     */
    public function deleteAction($id = 0)
    {
        $logger = $this->get('logger');
        $logger->info(sprintf('%s:%s %s %s',__CLASS__,__FUNCTION__,'DELETE ACTION',$id));
        return $this->render('UserBundle:Default:index.html.twig');
    }
    
    /**
     * @Route("/userProfile/edit/{id}", name="EditUser")
     */
    public function editAction($id = 0)
    {
        $logger = $this->get('logger');
        $logger->info(sprintf('%s:%s %s %s',__CLASS__,__FUNCTION__,'EDIT ACTION',$id));
        return $this->render('UserBundle:Default:index.html.twig');
    }
    
    /**
     * @Route("/userProfile/addPicture/{id}/{pictureUrl}", name="AddUserPicture")
     */
    public function loadPictureAction($id = 0,$pictureUrl = null)
    {
        $logger = $this->get('logger');
        $logger->info(sprintf('%s:%s %s %s %s',__CLASS__,__FUNCTION__,'LOAD PICTURE',$id,$pictureUrl));
        return $this->render('UserBundle:Default:index.html.twig');
    }
}
