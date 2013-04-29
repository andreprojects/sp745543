<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/[:username][/:action][/:id_ads][/:url_ads]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Perfil',
                        'action'     => 'index',
                    ),
                ),

              /*
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                   
                    'action' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '[:action][/:msg_id]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Index',
                                'action'     => 'index'
                            )
                        )
                    ),
                    

                    
                ),
                
                */
            ),
            
			
            
            'home-message' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/home[/:tipo][/:ref][/:cod_msg]',
                    'constraints' => array(
                        'tipo'=> '[a-z]+',
                        'cod_msg'=> '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),

            'list-ads-perfil' => array(//sem auth
                'type' => 'segment',
                'options' => array(
                    'route' => '/h/listadsperfil[/:id_usuario][/:username][/:col_order][/:type_order][/page[/:page]]',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Perfil',
                        'action'   => 'listads'
                    )
                )
            ),

            'search' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/h/search',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Search',
                        'action'  => 'index'
                        
                    )
                )
            ),

            'list-search' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/h/listsearch[/:word][/:col_order][/:type_order][/page[/:page]]',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Search',
                        'action'  => 'list'
                        
                    )
                )
            ),

            'servico-app' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/h/servico[/:action]',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Servico',
                        'action'  => 'adwords'
                        
                    )
                )
            ),


            'faleconosco' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/h/faleconosco',
                    'defaults' => array(
                      'controller' => 'Application\Controller\FaleConosco',
                        'action'  => 'index'
                        
                    )
                )
            ),
            'conta' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/h/conta',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Conta',
                        'action'  => 'index'
                        
                    )
                )
            ),
            'email-auth-pergunta' => array(//sem auth
                'type' => 'segment',
                'options' => array(
                    'route' => '/h/email-auth-pergunta/:id_anuncio/:id_pergunta/:email/:token',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Pergunta',
                        'action'  => 'emailauth'
                    )
                )
            ),
            'pergunta-publica' => array(//sem auth
                'type' => 'segment',
                'options' => array(
                    'route' => '/h/pergunta-publica[/:id_ads]',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Pergunta',
                        'action'  => 'index'
                        
                    )
                )
            ),
            'list-pergunta-publica' => array(//sem auth
                'type' => 'segment',
                'options' => array(
                    'route' => '/h/listperguntapublica[/:id_ads][/:col_order][/:type_order][/page[/:page]]',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Pergunta',
                        'action'   => 'listaperguntapublica'
                    )
                )
            ),
            'perguntas' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/h/perguntas[/:id_ads]/:action[/:id_pergunta]',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Pergunta',
                        'action'   => 'listapergunta'
                    )
                )
            ),
            'list-pergunta-conteudo' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/h/list-pergunta-conteudo[/:id_ads][/:col_order][/:type_order][/page[/:page]]',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Pergunta',
                        'action'   => 'listaperguntaconteudo'
                    )
                )
            ),
            'reposta-pergunta' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/h/resposta-pergunta[/:id_pergunta][/:tipo]',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Pergunta',
                        'action'   => 'respostapergunta'
                    )
                )
            ),
            'ganhe' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/ganhe',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Ganhe',
                        'action'  => 'index'
                        
                    )
                )
            ),
            'quem-somos' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/h/quem-somos',
                    'defaults' => array(
                      'controller' => 'Application\Controller\QuemSomos',
                        'action'  => 'index'
                        
                    )
                )
            ),
            'faq' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/h/faq',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Faq',
                        'action'  => 'index'
                        
                    )
                )
            ),
            'login-iframe' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/login-iframe',
                    'defaults' => array(
                      'controller' => 'Application\Controller\Index',
                        'action'  => 'loginIframe'
                        
                    )
                )
            ),
            'login' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                      'controller' => 'Login\Controller\Login',
                        'action'  => 'index'
                        
                    )
                )
            ),
            'display-images' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/h/display-images[/:id][/:cod]',
                    'defaults' => array(
                      'controller' => 'Application\Controller\MeusAnuncios',
                        'action'  => 'displayimage'
                        
                    )
                )
            ),
            'meus-anuncios' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/h/meus-anuncios[/:action][/:id][/:cod]',
                    'defaults' => array(
                      'controller' => 'Application\Controller\MeusAnuncios',
                        'action'  => 'index'
                        
                    )
                )
            ),
            'meus-anuncios-list' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/h/meus-anuncios-list[/:col_order][/:type_order][/page[/:page]]',
                    'defaults' => array(
                      'controller' => 'Application\Controller\MeusAnuncios',
                        'action'   => 'list'
                    )
                )
            ),

            'convite' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/h/convite[/:action][/:id]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Convite',
                        'action'  => 'index'  
                    )
                )
            ),
            'indicado' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/h/indicado[/:id][/:token]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Indicado',
                        'action'  => 'index'  
                    )
                )
            ),
            'admin' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                      'controller' => 'Admin\Controller\Index',
                        'action'  => 'index'
                        
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // Segment route for viewing one blog post
                    'servico' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/servico[/:action][/:id]',
                            'defaults' => array(
                            	'controller' => 'Admin\Controller\Servico',
                                'action' => 'index'
                            )
                        )
                    ),

                    'plano' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/plano[/:action][/:id]',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Plano',
                                'action' => 'index'
                            )
                        )
                    ),
                   
                )
            ),
            'reminder' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/h/reminder',
                    'defaults' => array(
                      'controller' => 'Login\Controller\Reminder',
                      'action'  => 'index'
                        
                    )
                )
            ),
            'register' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/h/register',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Login\Controller',
                        'controller'    => 'Login\Controller\Register',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // Segment route for viewing one blog post
                    'post' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/[:action][/:id]',
                            'defaults' => array(
                                'action' => 'generation'
                            )
                        )
                    ),
                    'step2' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/step2[/:token]',
                            'defaults' => array(
                              'action' => 'step2'
                            )
                        )
                    ),
                )
                
            ),
            'changepassword' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/h/changepassword[/:email][/:token]',
                    'defaults' => array(
                        'controller' => 'Login\Controller\ChangePassword',
                        'action'     => 'index',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/h/logout',
                    'defaults' => array(
                        'action' => 'logout',
                        'controller'=>'Login\Controller\Login'
                    ),
                ),
            ),
            'area-edit' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/area/edit[/:area][/:action][/:area-sel]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Area',
                        'action'     => 'index',
                    ),
                ),
            ),
            'area-edit-myimg' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/area/edit/myimg[/:img-sel]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Area',
                        'action'     => 'myimg',
                    ),
                ),
            ),
            'area-add' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/area/add[/:area]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'add',
                    ),
                ),
            ),
            'area-remove' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/area/remove[/:area]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'remove',
                    ),
                ),
            ),
            
			'carrinho' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/carrinho[/:area]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Carrinho',
                        'action'     => 'index',
                    ),
                ),
            ),
            
			'finish-carrinho' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/carrinho/finish',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Carrinho',
                        'action'     => 'finish',
                    ),
                ),
            ),
            
			'minha-conta' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/minhaconta',
                    'defaults' => array(
                        'controller' => 'Application\Controller\MinhaConta',
                        'action'     => 'index',
                    ),
                ),
            ),
            
			'change-my-data' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/changemydata',
                    'defaults' => array(
                        'controller' => 'Application\Controller\ChangeMyData',
                        'action'     => 'index',
                    ),
                ),
            ),
            
			'list-areas' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/listareas',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'list-areas',
                    ),
                ),
            ),
            
			'credito-post' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/creditopost',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Credito',
                        'action'     => 'index',
                    ),
                ),
            ),
            
			'console' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/h/console',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Credito',
                        'action'     => 'console',
                    ),
                ),
            ),
            
			'listuserall' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/listuserall',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Credito',
                        'action'     => 'listuserall',
                    ),
                ),
            ),
            
            
        ),    
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index'       => 'Application\Controller\IndexController',
            'Application\Controller\Carrinho'    => 'Application\Controller\CarrinhoController',
            'Application\Controller\FaleConosco' => 'Application\Controller\FaleConoscoController',
            'Application\Controller\Afiliados' 	 => 'Application\Controller\AfiliadosController',
            'Application\Controller\Ganhe' 	 	 => 'Application\Controller\GanheController',
            'Application\Controller\QuemSomos' 	 => 'Application\Controller\QuemSomosController',
            'Application\Controller\Faq' 	 	 => 'Application\Controller\FaqController',
            'Application\Controller\Area'        => 'Application\Controller\AreaController',
            'Application\Controller\MinhaConta'  => 'Application\Controller\MinhaContaController',
            'Application\Controller\ChangeMyData'=> 'Application\Controller\ChangeMyDataController',
            'Application\Controller\Credito'	 => 'Application\Controller\CreditoController',
            'Login\Controller\Login' 			 => 'Login\Controller\LoginController',
            'Login\Controller\Reminder' 		 => 'Login\Controller\ReminderController',
            'Login\Controller\Register' 		 => 'Login\Controller\RegisterController',
            'Login\Controller\ChangePassword' 	 => 'Login\Controller\ChangePasswordController',
            'Admin\Controller\Index' 			 => 'Admin\Controller\IndexController',
            'Admin\Controller\Servico' 			 => 'Admin\Controller\ServicoController',
            'Admin\Controller\Plano'             => 'Admin\Controller\PlanoController',
            'Application\Controller\MeusAnuncios'=> 'Application\Controller\MeusAnunciosController',
            'Application\Controller\Convite'     => 'Application\Controller\ConviteController',
            'Application\Controller\Indicado'   => 'Application\Controller\IndicadoController',
            'Application\Controller\Perfil'     => 'Application\Controller\PerfilController',
            'Application\Controller\Conta'      => 'Application\Controller\ContaController',
            'Application\Controller\Pergunta'   => 'Application\Controller\PerguntaController',
            'Application\Controller\Search'     => 'Application\Controller\SearchController',
            'Application\Controller\Servico'    => 'Application\Controller\ServicoController',
            
        ),
    ),
    
    'module_layouts' => array(
      'Application' => 'layout/layout',
      //'Login'       => 'layout/layout'
      'Admin'       => 'layout/layout-admin'
    ),
    
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            //'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ),
            ),
        ),
    ),
);