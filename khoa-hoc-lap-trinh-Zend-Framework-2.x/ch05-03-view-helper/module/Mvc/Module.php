<?php

namespace Mvc;

use Zend\ServiceManager\ServiceManager;

use Zend\EventManager\SharedEventManager;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;

class Module
{
	public function getServiceConfig(){
		return array(
				'invokables' 		=> array(),		// done		đăng ký 1 dịch vụ là 1 thể hiện của một lớp 
				'factories' 		=> array(		// đăng ký 1 dịch vụ là 1 thể hiện của một lớp (lớp phụ thuộc nhiều lớp khác)
					'userservice' 	=> function($sm){
						$fbService		= $sm->get('Mvc\Service\Facebook');
						$mailService	= new \Mvc\Service\MailService();
						$userService	= new \Mvc\Service\UserService($fbService, $mailService);
						return $userService;
					},
				),		
				'abstract_factories'=> array(),		// done		trường hợp dự phòng khi tên dịch vụ chưa được khai báo
				'aliases' 			=> array(),		// done		đặt tên mới cho dịch vụ đã tồn tại
				'services' 			=> array(),		// done		đăng ký 1 dịch vụ ứng với một đối tượng
				'initializers' 		=> array(		// done		định nghĩa hành động khi các dịch vụ được gọi
// 					function($instance, $sm){		
// 						if($instance instanceof \Mvc\Service\UserService){
// 							$instance->setFacebook('www.zend.vn/forum');
// 						}
// 					},
						
				),	
				'shared' 			=> array(),		// done		chia sẻ dịch vụ
		);
	}
	
	public function onBootstrap(MvcEvent $e)
	{		
		$serviceManager		= $e->getApplication()->getServiceManager();
		$eventManager       = $e->getApplication()->getEventManager();
		// $eventManager->attach('render',array($this, 'setTitle'));
		
		// 
		// $moduleRouteListener = new ModuleRouteListener();
		// $moduleRouteListener->attach($eventManager);
		// $eventManager->attach(new \ZendVN\Event\ShowInfoListener());
		
		// $sm	= new ServiceManager();
		// $sm->setShared($name, $isShared)
		// $userService	= $serviceManager->get('userservice');
	}
	
	public function setTitle($e){
		$matches	= $e->getRouteMatch();
		$moduleName	= __NAMESPACE__;
		$controller	= $matches->getParam('controller');
		$action		= $matches->getParam('action');

		$serviceManager		= $e->getApplication()->getServiceManager();
		$viewHelper			= $serviceManager->get('viewHelperManager');
		
		$headTitle			= $viewHelper->get('headTitle');
		$headTitle->append($moduleName);
		$headTitle->append($controller);
		$headTitle->append($action);
		$headTitle->setSeparator(' - ');

	}
	
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getViewHelperConfig(){
    	return array(
// 			'invokables' 		=> array(
// 				'sayhello'		=> '\ZendVN\View\Helper\SayHello',
// 			),
    			
    	);
    }
}
