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
use Tools\file\upload\LoadImageFile;
use Tools\file\upload\LoadFile;

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
                    'Image' => $user->getImageUrl()
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
                    'Image' => $user->getImageUrl()
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
                $logger->error(sprintf('%s:%s NOT FOUND RECORD [Id:%s]',__CLASS__,__FUNCTION__,$user_id));
               
                $respStatus = Response::HTTP_NOT_FOUND;
                $data = array(
                    'message' => sprintf('NOT FOUND RECORD [Id:%s]',$user_id) 
                );
                
            }else{
                /* @TODO logic for iNSERT new RECORD whit ID = $user_id
                 * Hay que implemetar
                 * VALIDACION URL  email e IMAGENES (PATTERN y disponibilidad)
                 * UPLOAD de la imagen a disco (tipo de imagen, tamaño, guardado en disco y asignacion del fullpath
                 *
                 */
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
                    'Image' => $user->getImageUrl()
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
     * @Route("/userProfile/{user_id}/addPicture", name="AddUserPicture")
     * @Method({"PUT"})
     */
    public function loadPictureAction($user_id = 0,$picture_url = null)
    {
        $logger = $this->get('logger');
        $respStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
        $request = Request::createFromGlobals();
        
        $putParams = $request->query->all();
        
        $imageUrl = ( array_key_exists('imageUrl',$putParams) &&  !empty($putParams['imageUrl']) ? $putParams['imageUrl'] : false );
        
        
        if(filter_var($user_id , FILTER_VALIDATE_INT) && $user_id > 0 ){
            $logger->debug(sprintf('%s:%s RECEIVED INT [Id:%s]',__CLASS__,__FUNCTION__,$user_id));
            /** @var src/IntrawayBundle\Entity\User $user */
            $user = $this->getDoctrine()
            ->getRepository('UserBundle:User')
            ->find($user_id);
            var_dump($user,$imageUrl);
            if (!$user ) {
                $logger->error(sprintf('%s:%s NOT FOUND RECORD [Id:%s]',__CLASS__,__FUNCTION__,$user_id));
                $respStatus = Response::HTTP_NOT_FOUND;
                $data = array(
                    'message' => sprintf('NOT FOUND RECORD [Id:%s]',$user_id) 
                );
                
            }else if (!$imageUrl) {
                $logger->error(sprintf('%s:%s NOT FOUND PARAM "imageurl" [Id:%s]',__CLASS__,__FUNCTION__,$user_id));
                $respStatus = Response::HTTP_PRECONDITION_FAILED;
                $data = array(
                    'message' => sprintf('NOT FOUND PARAM "imageurl" [Id:%s]',$user_id) 
                );
                
            }else{
                /* @TODO logic for iNSERT new RECORD whit ID = $user_id
                 * Hay que implemetar
                 * VALIDACION URL  IMAGENES (PATTERN y disponibilidad)
                 * UPLOAD de la imagen a disco (tipo de imagen, tamaño, guardado en disco y asignacion del fullpath
                 *
                 */
                $em = $this->getDoctrine()->getManager();
                if($imageUrl) $user->setImageUrl($imageUrl);
                $em->persist($user);
                $em->flush();
                
                /** UPLOAD IMAGE FROM URL **/
                
                $upload_folder = $this->container->getParameter('upload_files');
                
                $lif = new LoadImageFile();
                $lif->setUploadForlder($upload_folder);
                $lif->setUrl($imageUrl);
                
                $lif->loadFile();
                
                /***************************/
                
                if( !$lif->isErr() ){
                    $user->setImagePath($lif->getFilename());
                    $logger->debug(sprintf('%s:%s HAS FOUND AND UPDATED RECORD [Id:%s] [Name:%s]',__CLASS__,__FUNCTION__,$user->getId(),$user->getName()));
                    $respStatus = Response::HTTP_OK;
                    
                    $data = array(
                        'id'=> $user->getId(),
                        'name' => $user->getName(),
                        'email' => $user->getEmail(),
                        'Image' => $user->getImageUrl()
                    );
                }else{
                    $respStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
                    
                    $logger->error(sprintf('%s:%s FILE UPLOAD ERROR [Id:%s] [ErrMsg:%s]',__CLASS__,__FUNCTION__,$user_id,$lif->getErr()));
                    $data= array(
                        'message' => sprintf('SORRY, BUT ID IS INVALID [Id:%s]',$user_id)
                    );
                }
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
}
