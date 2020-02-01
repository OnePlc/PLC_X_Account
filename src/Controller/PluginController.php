<?php
/**
 * AccountController.php - Main Controller
 *
 * Main Controller Account Module
 *
 * @category Controller
 * @package Account
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

declare(strict_types=1);

namespace OnePlace\Account\Controller;

use Application\Controller\CoreEntityController;
use Application\Model\CoreEntityModel;
use OnePlace\Account\Model\Account;
use OnePlace\Account\Model\AccountTable;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Adapter\AdapterInterface;

class PluginController extends CoreEntityController {
    /**
     * Account Table Object
     *
     * @since 1.0.0
     */
    protected $oTableGateway;

    /**
     * AccountController constructor.
     *
     * @param AdapterInterface $oDbAdapter
     * @param AccountTable $oTableGateway
     * @since 1.0.0
     */
    public function __construct(AdapterInterface $oDbAdapter,AccountTable $oTableGateway,$oServiceManager) {
        $this->oTableGateway = $oTableGateway;
        $this->sSingleForm = 'account-single';
        parent::__construct($oDbAdapter,$oTableGateway,$oServiceManager);

        if($oTableGateway) {
            # Attach TableGateway to Entity Models
            if(!isset(CoreEntityModel::$aEntityTables[$this->sSingleForm])) {
                CoreEntityModel::$aEntityTables[$this->sSingleForm] = $oTableGateway;
            }
        }
    }

    public function testFunction() {
        /**
         * Execute your hook code here
         *
         * optional: return an array to attach data to View
         * otherwise return true
         */
        return ['testData'=>'here i am'];
    }

    public function indexAction() {
        $this->layout('layout/json');

        echo 'index';

        return false;
    }
}
