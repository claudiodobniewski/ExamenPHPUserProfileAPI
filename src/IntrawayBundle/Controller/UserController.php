<?php

namespace IntrawayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/userProfile/{user_id}", name="GetUser")
     * @Method({"GET"})
     */
    public function getAction($user_id=0)
    {
        $logger = $this->get('logger');
        $respStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
        $request = Request::createFromGlobals();
        //$get_id = $request->query->get('user_id');
        /*
        $content = $request->getContent();
        $logger->info(sprintf('%s:%s [Body:%s]',__CLASS__,__FUNCTION__,$content ));
        
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        //$normalizers->setIgnoredAttributes(array('imageUrl'));
        $serializer = new Serializer($normalizers, $encoders);
        
        //$obj = $serializer->deserialize($content,User::class,'json');
        $obj = json_decode($content);
        */
        //$logger->info(sprintf('%s:%s %s [Id:%s] [Name:%s]',__CLASS__,__FUNCTION__,'GET ACTION',$obj->id,$obj->name ));
        
        
        if(filter_var($user_id , FILTER_VALIDATE_INT) && $user_id > 0 ){
            $logger->debug(sprintf('%s:%s RECEIVED INT [Id:%s]',__CLASS__,__FUNCTION__,$user_id));
            /** var ibe\User $object */
            $user = $this->getDoctrine()
            ->getRepository('UserBundle:User')
            ->find($user_id);
            
            if (!$user) {
                $logger->error(sprintf('%s:%s NOT FOUND [Id:%s]',__CLASS__,__FUNCTION__,$user_id));
                /*
                throw $this->createNotFoundException(
                    'No product found for id '.$user_id
                    );
                    */
                $respStatus = Response::HTTP_NO_CONTENT;
                $data= array('message' => sprintf('NOT FOUND [Id:%s]',$user_id) );
            }else{
                $logger->debug(sprintf('%s:%s HAS FOUND [Id:%s] [Name:%s]',__CLASS__,__FUNCTION__,$user->getId(),$user->getName()));
                $respStatus = Response::HTTP_OK;
                $data = array(
                    'id'=> $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'imageUrl' => $user->getImageUrl()
                );
            }
        }else{
            $logger->error(sprintf('%s:%s SORRY, BUT ID IS INVALID [Id:%s]',__CLASS__,__FUNCTION__,$user_id));
            $respStatus = Response::HTTP_PRECONDITION_FAILED;
            $data= array('message' => sprintf('SORRY, BUT ID IS INVALID [Id:%s]',$user_id) );
        }
        
        
        //return $this->render('UserBundle:Default:index.html.twig',array('output' => $serializer->serialize($object, 'json') ));
        return new JsonResponse($data,$respStatus);
    }
    
    /**
     * @Route("/userProfile/{user_id}", name="DeleteUser")
     * @Method({"DELETE"})
     */
    public function deleteAction($user_id = 0)
    {
        $logger = $this->get('logger');
        $respStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
        $request = Request::createFromGlobals();
        
        if(filter_var($user_id , FILTER_VALIDATE_INT) && $user_id > 0 ){
            $logger->debug(sprintf('%s:%s RECEIVED INT [Id:%s]',__CLASS__,__FUNCTION__,$user_id));
            /** var IntrawayBundle\Entity\User $object */
            $user = $this->getDoctrine()
            ->getRepository('UserBundle:User')
            ->find($user_id);
            
            if (!$user) {
                $logger->error(sprintf('%s:%s NOT FOUND RECORD [Id:%s]',__CLASS__,__FUNCTION__,$user_id));
                /*
                throw $this->createNotFoundException(
                    'No product found for id '.$user_id
                    );
                    */
                $respStatus = Response::HTTP_NOT_FOUND;
                $data = array(
                    'message' => sprintf('NOT FOUND RECORD [Id:%s]',$user_id) 
                );
            }else{
                $em = $this->getDoctrine()->getManager();
                $em->remove($user);
                $em->flush();
                $logger->debug(sprintf('%s:%s HAS FOUND AND DELETED RECORD [Id:%s] [Name:%s]',__CLASS__,__FUNCTION__,$user->getId(),$user->getName()));
                $respStatus = Response::HTTP_OK;
                
                $data = array(
                    'id'=> $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'imageUrl' => $user->getImageUrl()
                );
            }
        }else{
            $logger->error(sprintf('%s:%s SORRY, BUT ID IS INVALID [Id:%s]',__CLASS__,__FUNCTION__,$user_id));
            $respStatus = Response::HTTP_PRECONDITION_FAILED;
            $data= array(
                'message' => sprintf('SORRY, BUT ID IS INVALID [Id:%s]',$user_id) 
            );
        }
        
        return new JsonResponse($data,$respStatus);
    }
    
    /**
     * @Route("/userProfile/{user_id}", name="EditUser")
     * @Method({"PUT"})
     */
    public function editAction($user_id = 0)
    {
        $logger = $this->get('logger');
        $respStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
        $request = Request::createFromGlobals();
        
        $putParams = $request->query->all();
        
        $name = ( array_key_exists('name',$putParams) &&  !empty($putParams['name']) ? $putParams['name'] : false );
        $email = ( array_key_exists('email',$putParams) &&  !empty($putParams['email']) ? $putParams['email'] : false );
        $imageUrl = ( array_key_exists('imageUrl',$putParams) &&  !empty($putParams['imageUrl']) ? $putParams['imageUrl'] : false );
        
        
        if(filter_var($user_id , FILTER_VALIDATE_INT) && $user_id > 0 ){
            $logger->debug(sprintf('%s:%s RECEIVED INT [Id:%s]',__CLASS__,__FUNCTION__,$user_id));
            /** var IntrawayBundle\Entity\User $object */
            $user = $this->getDoctrine()
            ->getRepository('UserBundle:User')
            ->find($user_id);
            
            if (!$user) {
                $logger->error(sprintf('%s:%s NOT FOUND RECORD [Id:%s] CREATE NEW',__CLASS__,__FUNCTION__,$user_id));
                /* @TODO logic for iNSERT new RECORD whit ID = $user_id */
                $respStatus = Response::HTTP_NOT_FOUND;
                $data = array(
                    'message' => sprintf('NOT FOUND RECORD [Id:%s]',$user_id) 
                );
                
            }else{
                $em = $this->getDoctrine()->getManager();
                if($name) $user->setName($name);
                if($email) $user->setEmail($email);
                if($imageUrl) $user->setImageUrl($imageUrl);
                $em->persist($user);
                $em->flush();
                $logger->debug(sprintf('%s:%s HAS FOUND AND UPDATED RECORD [Id:%s] [Name:%s]',__CLASS__,__FUNCTION__,$user->getId(),$user->getName()));
                $respStatus = Response::HTTP_OK;
                
                $data = array(
                    'id'=> $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'imageUrl' => $user->getImageUrl()
                );
            }
        }else{
            $logger->error(sprintf('%s:%s SORRY, BUT ID IS INVALID [Id:%s]',__CLASS__,__FUNCTION__,$user_id));
            $respStatus = Response::HTTP_PRECONDITION_FAILED;
            $data= array(
                'message' => sprintf('SORRY, BUT ID IS INVALID [Id:%s]',$user_id) 
            );
        }
        
        return new JsonResponse($data,$respStatus);
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
