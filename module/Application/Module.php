<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener,
    Zend\Mvc\MvcEvent,
    Zend\ModuleManager\ModuleManager;
use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;
	
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use DoctrineModule\Validator\ObjectExists as ObjectExistsValidator;
   
//use Login\Form\ReminderForm as ReminderForm;    

class Module {

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                	'Admin' 	  => __DIR__ . '/src/' . "Admin",
                    'Login' 	  => __DIR__ . '/src/' . "Login",
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap($e) {
    	
		
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
                    $controller = $e->getTarget();
                    $controllerClass = get_class($controller);
                    $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
                    $config = $e->getApplication()->getServiceManager()->get('config');
                    if (isset($config['module_layouts'][$moduleNamespace])) {
                    	$controller->layout($config['module_layouts'][$moduleNamespace]);
                    }
					
					//seta variavel no layout
					$e->getViewModel()->setVariable('controllerclass', $controllerClass);
                }, 98);
    }
	

    public function init(ModuleManager $moduleManager) {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
		
        $sharedEvents->attach("Application", 'dispatch', function($e) {
        				
        			$auth = new AuthenticationService;
                    $auth->setStorage(new SessionStorage("Login"));

                    $controller = $e->getTarget();
                    $matchedRoute = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();

                    if (!$auth->hasIdentity() and ($matchedRoute == "meus-anuncios")) {
                        return $controller->redirect()->toRoute('home');
                    }
                }, 99);
    }

    public function getServiceConfig() {

        return array(
            'factories' => array(
                
				'service_helper_session_login' => function($service){
					$helper = $service->get('viewhelpermanager')->get('UserIdentity');
					$session =$helper('Login'); 
					return ( $session != false? $session : false);
				},
				
				'service_area_upload_form' => function ($service) {
                     $form = new \Application\Form\AreaUploadForm();
                     return $form;
                },
                
                'service_area_step3_form' => function ($service) {
                     $form = new \Application\Form\AreaStep3Form();
                     return $form;
                },
				
                'service_faleconosco_form' => function ($service) {
                     $form = new \Application\Form\FaleConoscoForm();
                     return $form;
                },
                
                'service_faleconosco' => function($service) {
                    $obj = new \Application\Service\FaleConosco($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },
                
				'service_area_step3' => function($service) {
                    $obj = new \Application\Service\AreaStep3($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },
                
				'service_carrinho' => function($service) {
                    $obj = new \Application\Service\Carrinho($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },
                
				
				'service_register' => function($service) {
                    $obj = new \Login\Service\Register($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },
                
                'service_reminder' => function($service) {
                    $obj = new \Login\Service\Reminder($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },
                
                'service_changepassword' => function($service) {
                    $obj = new \Login\Service\ChangePassword($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },
                
				'service_changemydata' => function($service) {
                    $obj = new \Application\Service\ChangeMyData($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },
                
				'service_servico' => function($service) {
                    $obj = new \Admin\Service\Servico($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },

                'service_plano' => function($service) {
                    $obj = new \Admin\Service\Plano($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },

                'service_meusanuncios' => function($service) {
                    $obj = new \Application\Service\Anuncio($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },
                

                'service_convite' => function($service) {
                    $obj = new \Application\Service\Convite($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },
                
                'Login\Auth\Adapter' => function($service) {
                    return new \Login\Auth\Adapter($service->get('Doctrine\ORM\EntityManager'));
                },
                
				'Admin\Auth\Adapter' => function($service) {
                    return new \Admin\Auth\Adapter($service->get('Doctrine\ORM\EntityManager'));
                },
                
                'service_register_form' => function ($service) {
                	//echo $baseUrl = $service->get('request')->getbaseUrl();
					$baseUrl = "http://".$_SERVER['HTTP_HOST'];
					
                    $form = new \Login\Form\RegisterForm($baseUrl);
                    $emailInput = $form->getInputFilter()->get('email');
                    
                    $NoObjectExistsValidator = new \DoctrineModule\Validator\NoObjectExists(array(
                        'object_repository' => $service->get('Doctrine\ORM\EntityManager')->getRepository('Application\Entity\Usuario'),
                        'fields'            => 'email',
                        'messages' =>
                            array(
                                'objectFound' => 'Sorry guy, a user with this email %value% already exists !'
                            )
                    ));
                     //var_dump($ObjectExistsValidator->isValid('tess3@gmail.com'));
                    $emailInput->getValidatorChain()->addValidator($NoObjectExistsValidator);
                    return $form;
                    
                },

                'convite_form' => function ($service) {
                    //echo $baseUrl = $service->get('request')->getbaseUrl();
                    //$baseUrl = "http://".$_SERVER['HTTP_HOST'];
                    
                    $form = new \Application\Form\ConviteForm();
                    $emailInput = $form->getInputFilter()->get('email');
                    
                    $NoObjectExistsValidator = new \DoctrineModule\Validator\NoObjectExists(array(
                        'object_repository' => $service->get('Doctrine\ORM\EntityManager')->getRepository('Application\Entity\Usuario'),
                        'fields'            => 'email',
                        'messages' =>
                            array(
                                'objectFound' => 'Email %value% já cadastrado'
                            )
                    ));
                     //var_dump($ObjectExistsValidator->isValid('tess3@gmail.com'));
                    $emailInput->getValidatorChain()->addValidator($NoObjectExistsValidator);
                    return $form;
                    
                },

                'service_reminder_form' => function ($service) {
                    $baseUrl = $service->get('request')->getbaseUrl();
                    
                    $form = new \Login\Form\ReminderForm($baseUrl);
                    
                    $emailInput = $form->getInputFilter()->get('email');
                    $ObjectExistsValidator = new ObjectExistsValidator(array(
                        'object_repository' => $service->get('Doctrine\ORM\EntityManager')->getRepository('Application\Entity\Usuario'),
                        'fields'            => 'email',
                        'messages' =>
                            array(
                                'noObjectFound' => 'Sorry, this email %value% not exists !'
                            )
                    ));
					
                     //var_dump($ObjectExistsValidator->isValid('tess3@gmail.com'));
                    $emailInput->getValidatorChain()->addValidator($ObjectExistsValidator);
                    
                    return $form;
                    
                },

                'indicado_step2_form' => function ($service) {
                     $form = new \Application\Form\IndicadoStep2Form();
                     return $form;
                },

                'service_register_step2_form' => function ($service) {
                     $form = new \Login\Form\RegisterStep2Form();
                     return $form;
                },
                
				'service_change_my_data_form' => function ($service) {
                     $form = new \Application\Form\ChangeMyDataForm();
                     return $form;
                },
				
                'service_changepassword_form' => function ($service) {
                     $form = new \Login\Form\ChangePasswordForm();
                     return $form;
                },
                
				'service_servico_form' => function ($service) {
                     $form = new \Admin\Form\ServicoForm();
                     return $form;
                },

                'service_plano_form' => function ($service) {
                     $form = new \Admin\Form\PlanoForm();
                     $opt= $service->get('Doctrine\ORM\EntityManager')->getRepository('Application\Entity\Servico')->getServicoToCombobox();
                     $form->get('id_servico')->setValueOptions($opt);
                     return $form;
                },

                'service_meusanuncios_form' => function ($service) {
                     $form = new \Application\Form\AnuncioForm();
                     $helper = $service->get('viewhelpermanager')->get('UserIdentity');
                     //$session = $helper('Login')['user']->id; 
                     $form->get('id_usuario')->setValue($helper('Login')['user']->id);
                     return $form;
                },
                
				
                'resolver_files' => function($sm) {
                  
                  $map = new \Zend\View\Resolver\TemplateMapResolver(array(
                            'login/register/confirmation-email' => __DIR__ . '/view/login/register/confirmation-email.phtml',
                        ));
                  return new \Zend\View\Resolver\TemplateMapResolver($map);
                },
				
				'service_resolver_view_servico' => function($sm) {
                  
                  $map = new \Zend\View\Resolver\TemplateMapResolver(array(
                            'admin/servico/edit' => __DIR__ . '/view/admin/servico/index.phtml',
                        ));
                  return new \Zend\View\Resolver\TemplateMapResolver($map);
                }
                
            ),
        );
    }

    public function getViewHelperConfig() {
        return array(
            'invokables' => array(
                'UserIdentity' => new View\Helper\UserIdentity(),
                'SessionSelection' => new View\Helper\SessionSelection()
            )
        );
    }


}
