<?php
/**
 * Module.php - Module Class
 *
 * Module Class File for Account Module
 *
 * @category Config
 * @package Account
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

namespace OnePlace\Account;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Mvc\MvcEvent;
use Laminas\ModuleManager\ModuleManager;
use Laminas\Session\Config\StandardConfig;
use Laminas\Session\SessionManager;
use Laminas\Session\Container;
use Application\Controller\CoreEntityController;
use OnePlace\Account\Controller\PluginController;

class Module {
    /**
     * Module Version
     *
     * @since 1.0.1
     */
    const VERSION = '1.0.1';

    /**
     * Load module config file
     *
     * @since 1.0.0
     * @return array
     */
    public function getConfig() : array {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Load Models
     */
    public function getServiceConfig() : array {
        return [
            'factories' => [
                # Account Module - Base Model
                Model\AccountTable::class => function($container) {
                    $tableGateway = $container->get(Model\AccountTableGateway::class);
                    return new Model\AccountTable($tableGateway,$container);
                },
                Model\AccountTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Account($dbAdapter));
                    return new TableGateway('account', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    /**
     * Load Controllers
     */
    public function getControllerConfig() : array {
        return [
            'factories' => [
                # Plugin Example Controller
                Controller\PluginController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    return new Controller\PluginController(
                        $oDbAdapter,
                        $container->get(Model\AccountTable::class),
                        $container
                    );
                },
                # Account Main Controller
                Controller\AccountController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    $tableGateway = $container->get(Model\AccountTable::class);
                    # hook plugin
                    CoreEntityController::addHook('account-add-before',(object)['sFunction'=>'testFunction','oItem'=>new PluginController($oDbAdapter,$tableGateway,$container)]);
                    return new Controller\AccountController(
                        $oDbAdapter,
                        $container->get(Model\AccountTable::class),
                        $container
                    );
                },
                # Api Plugin
                Controller\ApiController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    return new Controller\ApiController(
                        $oDbAdapter,
                        $container->get(Model\AccountTable::class),
                        $container
                    );
                },
                # Export Plugin
                Controller\ExportController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    return new Controller\ExportController(
                        $oDbAdapter,
                        $container->get(Model\AccountTable::class),
                        $container
                    );
                },
                # Search Plugin
                Controller\SearchController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    return new Controller\SearchController(
                        $oDbAdapter,
                        $container->get(Model\AccountTable::class),
                        $container
                    );
                },
            ],
        ];
    }
}
