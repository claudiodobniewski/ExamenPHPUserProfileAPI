<?php

namespace IntrawayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use IntrawayBundle\Entity as ibe;
use IntrawayBundle\Tools\file\upload as tfu;
use IntrawayBundle\Entity\User;
use IntrawayBundle\Tools\validation\PatternValidation;
use IntrawayBundle\Tools\ControllerHelper;

class UserController extends Controller
{
    private $trxId;
    
    public function __construct(){
        
        $this->trxId = ControllerHelper::getRandomId();
    }
    
    /**
     * @Route("/userProfile/{user_id}", name="setUser")
     * @Method({"POST"})
     */
    public function newAction()
    {
        $logger = $this->get('logger');
        
        $logger->error(sprintf('%s:%s NEW USER OP NOT ALLOWED [Id:%s]',__CLASS__,__FUNCTION__,$this->trxId ));
        $respStatus = Response::HTTP_METHOD_NOT_ALLOWED;
        $data= array('message' => 'NEW USER OP NOT ALLOWED' );
        return new JsonResponse($data,$respStatus);
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
            $logger->debug(sprintf('%s:%s RECEIVED INT [Id:%s] [UserdId:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user_id));
            /** var ibe\User $object */
            $user = $this->getDoctrine()
            ->getRepository('UserBundle:User')
            ->find($user_id);
            
            if (!$user) {
                $logger->error(sprintf('%s:%s NOT FOUND USER [Id:%s] [UserId:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user_id));
                /*
                throw $this->createNotFoundException(
                    'No product found for id '.$user_id
                    );
                    */
                $respStatus = Response::HTTP_NOT_FOUND;
                $data= array('message' => sprintf('NOT FOUND USER [Id:%s] [UserId:%s]',$this->trxId,$user_id) );
            }else{
                $logger->debug(sprintf('%s:%s HAS FOUND [Id:%s] [UserId:%s] [Name:%s]',__CLASS__,__FUNCTION__,$user->getId(),$this->trxId,$user->getName()));
                $respStatus = Response::HTTP_OK;
                $image = $user->getImage() ? $this->getUserImageFullUrl($request, $user->getImage()) : null;
                $data = array(
                    'id'=> $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'Image' => $image
                );
            }
        }else{
            $logger->error(sprintf('%s:%s SORRY, BUT ID IS INVALID [Id:%s] [UserId:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user_id));
            $respStatus = Response::HTTP_PRECONDITION_FAILED;
            $data= array('message' => sprintf('SORRY, BUT ID IS INVALID [Id:%s] [UserId:%s]',$this->trxId,$user_id) );
        }
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
            $logger->debug(sprintf('%s:%s RECEIVED INT [Id:%s] [UserId:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user_id));
            /** var IntrawayBundle\Entity\User $object */
            $user = $this->getDoctrine()
            ->getRepository('UserBundle:User')
            ->find($user_id);
            
            if (!$user) {
                $logger->error(sprintf('%s:%s NOT FOUND RECORD [Id:%s] [UserId:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user_id));
                /*
                throw $this->createNotFoundException(
                    'No product found for id '.$user_id
                    );
                    */
                $respStatus = Response::HTTP_NOT_FOUND;
                $data = array(
                    'message' => sprintf('NOT FOUND RECORD [Id:%s] [UserID:%s]',$this->trxId,$user_id) 
                );
            }else{
                $em = $this->getDoctrine()->getManager();
                $em->remove($user);
                $em->flush();
                $logger->debug(sprintf('%s:%s HAS FOUND AND DELETED RECORD [Id:%s] [UserId:%s] [Name:%s] [Email:%s] [Image:%s]',__CLASS__,__FUNCTION__,
                    $this->trxId,$user->getId(),$user->getName(),$user->getEmail(),$user->getImage()));
                $respStatus = Response::HTTP_OK;
                
                $image = $user->getImage() ? $this->getUserImageFullUrl($request, $user->getImage()) : null;
                $data = array(
                    'id'=> $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'Image' => $image
                );
            }
        }else{
            $logger->error(sprintf('%s:%s SORRY, BUT ID IS INVALID [Id:%s] [UserId:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user_id));
            $respStatus = Response::HTTP_PRECONDITION_FAILED;
            $data= array(
                'message' => sprintf('SORRY, BUT ID IS INVALID [Id:%s] [UserId:%s]',$this->trxId,$user_id) 
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
            $logger->debug(sprintf('%s:%s RECEIVED INT [Id:%s] [UserId:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user_id));
            /** var IntrawayBundle\Entity\User $object */
            $user = $this->getDoctrine()
            ->getRepository('UserBundle:User')
            ->find($user_id);
            
            if (!$user) {
                $logger->error(sprintf('%s:%s NOT FOUND RECORD [Id:%s] [UserId:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user_id));
               
                $respStatus = Response::HTTP_NOT_FOUND;
                $data = array(
                    'message' => sprintf('NOT FOUND RECORD [Id:%s] [UserId:%s]',$this->trxId,$user_id) 
                );
                
            }else{
                
                /**
                 * Validate long of  new name, at least 10 chars
                 */
                if($name && strlen($name) >= 10){
                    $user->setName($name);
                }else{
                    $logger->warn(sprintf('%s:%s BAD LONG OF NAME (at least 10 chars long) [Id:%s] [Name:%s]',__CLASS__,__FUNCTION__,$this->trxId,$name));;
                }
                /**
                 * Validate format of new email
                 */
                if($email && PatternValidation::validateEmailPattern($email)) {
                    $user->setEmail($email);
                }else{
                    $logger->warn(sprintf('%s:%s BAD FORMAT OF EMAIL [Id:%s] [UserId:%s] [Email:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user->getId(),$email));
                }
                //if($imageUrl) $user->setImage($imageUrl);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $logger->debug(sprintf('%s:%s HAS FOUND AND UPDATED RECORD [Id:%s] [UserId:%s] [Name:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user->getId(),$user->getName()));
                $respStatus = Response::HTTP_OK;
                $image = $user->getImage() ? $this->getUserImageFullUrl($request, $user->getImage()) : null;
                
                $data = array(
                    'id'=> $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'Image' => $image
                );
            }
        }else{
            $logger->error(sprintf('%s:%s SORRY, BUT USER ID IS INVALID [Id:%s] [UserId:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user_id));
            $respStatus = Response::HTTP_PRECONDITION_FAILED;
            $data= array(
                'message' => sprintf('SORRY, BUT ID USER IS INVALID [Id:%s] [USerId:%s]',$this->trxId,$user_id) 
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
        /** @var Logger $logger */
        $logger = $this->get('logger');
        $respStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
        $request = Request::createFromGlobals();
        
        $putParams = $request->query->all();
        
        $imageUrl = ( array_key_exists('imageUrl',$putParams) &&  !empty($putParams['imageUrl']) ? $putParams['imageUrl'] : false );
        
        if(filter_var($user_id , FILTER_VALIDATE_INT) && $user_id > 0 ){
            $logger->debug(sprintf('%s:%s RECEIVED INT [Id:%s] [UserID:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user_id));
            
            
            /** @var src/IntrawayBundle\Entity\User $user */
            $user = $this->getDoctrine()
            ->getRepository('UserBundle:User')
            ->find($user_id);
            
            if (!$user ) {
                $logger->error(sprintf('%s:%s NOT FOUND RECORD [Id:%s] [UserId:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user_id));
                $respStatus = Response::HTTP_NOT_FOUND;
                $data = array(
                    'message' => sprintf('NOT FOUND RECORD [Id:%s] [USerId:%s]',$this->trxId,$user_id) 
                );
                
            }else if (!$imageUrl) {
                $logger->error(sprintf('%s:%s NOT FOUND PARAM "imageurl" [Id:%s] [UserId:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user_id));
                $respStatus = Response::HTTP_PRECONDITION_FAILED;
                $data = array(
                    'message' => sprintf('NOT FOUND PARAM "imageurl" [Id:%s] [UserId:%s]',$this->trxId,$user_id) 
                );
                
            }else{
                $logger->debug(sprintf('%s:%s PRE-PROCESS UPLOAD [Id:%s] [UserId:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user_id));
                /* @TODO logic for iNSERT new RECORD whit ID = $user_id
                 * Hay que implemetar borrado de la imagen anterior si se reemplaza
                 */
                
                $em = $this->getDoctrine()->getManager();
                
                /** UPLOAD IMAGE FROM URL **/
                
                $web_basedir = $this->container->getParameter('base_web_rootdir');
                $upload_folder = $this->container->getParameter('upload_files');
                
                
                $lif = new tfu\LoadImageFile();
                $lif->setUploadForlder($web_basedir.$upload_folder);
                $lif->setUrl($imageUrl);
                $lif->setFilename($user_id);
                
                $lif->loadFile();
                
                /***************************/
                
                /**
                 * Validate errors on upload process
                 */
                if( !$lif->isErr() ){
                    
                    $oldImage = $user->getImage() ? $user->getImage() : null;
                    //$baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
                    $image_local_url = $this->getUserImageFullUrl($request,$lif->getFilename()) ;
                    
                    $user->setImage($lif->getFilename());
                    $em->persist($user);
                    $em->flush();
                    $oldFullPath=$this->getUserImageFullpath($oldImage);
                    
                    /**
                     * If exists was previus file,and  different of current file, delete it
                     */
                    !($lif->getFilename() === $oldImage) && file_exists($this->getUserImageFullpath($oldImage)) && unlink($this->getUserImageFullpath($oldImage));
                    
                    $logger->debug(sprintf('%s:%s HAS FOUND AND UPDATED RECORD [Id:%s] [UserId:%s] [File:%s] [Url:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user->getId(),$lif->getFilename(),$lif->getUrl()));
                    $respStatus = Response::HTTP_OK;
                    $image = $user->getImage() ? $this->getUserImageFullUrl($request, $lif->getFilename()) : null;
                    //var_dump($this->getUserImageFullpath($lif->getFilename()),$this->getUserImageFullUrl($request,$lif->getFilename()));
                    
                    $data = array(
                        'id'=> $user->getId(),
                        'name' => $user->getName(),
                        'email' => $user->getEmail(),
                        'Image' => $image
                    );
                }else{
                    $logger->error(sprintf('%s:%s FILE UPLOAD ERROR [Id:%s] [UserID:%s] [ErrMsg:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user_id,$lif->getErr()));
                    $respStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
                    $data= array(
                        'message' => sprintf('SORRY, FILE UPLOAD ERROR [Id:%s] [UserId:%s] [ErrMsg:%s]',$this->trxId,$user_id,$lif->getErr())
                    );
                }
            }
        }else{
            $logger->error(sprintf('%s:%s SORRY, BUT ID IS INVALID [Id:%s] [UserId:%s]',__CLASS__,__FUNCTION__,$this->trxId,$user_id));
            $respStatus = Response::HTTP_PRECONDITION_FAILED;
            $data= array(
                'message' => sprintf('SORRY, BUT ID IS INVALID [Id:%s] [UserId:%s]',$this->trxId,$user_id) 
            );
        }
        
        return new JsonResponse($data,$respStatus);
    }
    
    /**
     * 
     * @param string $imageFilename
     * @return string
     */
    protected function getUserImageFullpath($imageFilename){
        
        $web_basedir = $this->container->getParameter('base_web_rootdir');
        $upload_folder = $this->container->getParameter('upload_files');
        
        return $web_basedir.$upload_folder.$imageFilename;
    }
    
    /**
     * 
     * @param Request $request
     * @param string $imageFilename
     * @return string
     */
    protected function getUserImageFullUrl(Request $request,$imageFilename){
        
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $upload_folder = $this->container->getParameter('upload_files');
        return $baseurl.$upload_folder.$imageFilename;
    }
}
