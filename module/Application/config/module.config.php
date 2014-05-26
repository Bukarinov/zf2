<?php

namespace Application;

use Zend\ServiceManager\ServiceManager;
use Doctrine\ODM\MongoDB\DocumentManager;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            )
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'VacanciesFilter' => function(ServiceManager $serviceManager) {
                $form = new Form\VacanciesFilter();
                /* @var DocumentManager $documentManager */
                $documentManager = $serviceManager->get('doctrine.documentmanager.odm_default');
                $form->setObjectManager($documentManager);
                $form->init();

                return $form;
            },
            'VacancyService' => function(ServiceManager $serviceManager) {
                $service = new Service\Vacancy();
                /* @var DocumentManager $documentManager */
                $documentManager = $serviceManager->get('doctrine.documentmanager.odm_default');
                $service->setObjectManager($documentManager);

                return $service;
            },
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_models' => array(
                'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Model'),
            ),
            'odm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Model' => __NAMESPACE__ . '_models',
                ),
            ),
        ),
    ),
);
