<?php

namespace IntrawayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use IntrawayBundle\Entity as ibe;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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
     * 
     * 
     * @Route("/userProfile/get", name="GetUser")
     * @Method({"GET"})
     */
    public function getAction()
    {
        $logger = $this->get('logger');
        $request = Request::createFromGlobals();
        //$get_id = $request->query->get('user_id');
        $content = $request->getContent();
        
        $logger->info(sprintf('%s:%s [Body:%s]',__CLASS__,__FUNCTION__,$content ));
        
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        //$normalizers->setIgnoredAttributes(array('imageUrl'));
        $serializer = new Serializer($normalizers, $encoders);
        
        //$obj = $serializer->deserialize($content,User::class,'json');
        $obj = json_decode($content);
        
        //$logger->info(sprintf('%s:%s %s [Id:%s] [Name:%s]',__CLASS__,__FUNCTION__,'GET ACTION',$obj->id,$obj->name ));
        
        
        if($obj  && filter_var($obj->id , FILTER_VALIDATE_INT) && $obj->id > 0 ){
            $logger->debug(sprintf('%s:%s JSON DECODED Id:%s',__CLASS__,__FUNCTION__,$obj->id));
            $user = $this->getDoctrine()
            ->getRepository('UserBundle:User')
            ->find($obj->id);
            
            if (!$user) {
                $logger->debug(sprintf('%s:%s JSON DECODED BUT Id:%s NOT FOUND',__CLASS__,__FUNCTION__,$obj->id));
                /*
                throw $this->createNotFoundException(
                    'No product found for id '.$user_id
                    );
                    */
                $user = new ibe\User();
            }else{
                $logger->debug(sprintf('%s:%s JSON DECODED AND Id:%s HAS FOUND %s',__CLASS__,__FUNCTION__,$obj->id,$user->getName()));
            }
        }else{
            $logger->debug(sprintf('%s:%s JSON HASN\'T DECODED [Body:%s]',__CLASS__,__FUNCTION__,$content));
            $user = new ibe\User();
        }
        
        return $this->render('UserBundle:Default:index.html.twig',array('output' => $serializer->serialize($user, 'json') ));
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
