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

class AccountController extends CoreEntityController {
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

    /**
     * Account Index
     *
     * @since 1.0.0
     * @return ViewModel - View Object with Data from Controller
     */
    public function indexAction() {
        # Set Layout based on users theme
        $this->setThemeBasedLayout('account');

        # Check license
        if(!$this->checkLicense('user')) {
            $this->flashMessenger()->addErrorMessage('You have no active license for oneplace');
            $this->redirect()->toRoute('home');
        }

        $oRequest = $this->getRequest();
        if($oRequest->isPost()) {
            $sApiKey = $oRequest->getPost('plc_api_key');
            $sApiToken = $oRequest->getPost('plc_api_token');
            $sApiServer = $oRequest->getPost('plc_api_server');
            $sApiStore = $oRequest->getPost('plc_api_store');

            # Delete current settings
            CoreEntityController::$aCoreTables['settings']->delete(['settings_key'=>'license-server-apikey']);
            CoreEntityController::$aCoreTables['settings']->delete(['settings_key'=>'license-server-apitoken']);
            CoreEntityController::$aCoreTables['settings']->delete(['settings_key'=>'license-server-url']);
            CoreEntityController::$aCoreTables['settings']->delete(['settings_key'=>'store-server-apikey']);
            CoreEntityController::$aCoreTables['settings']->delete(['settings_key'=>'store-server-apitoken']);
            CoreEntityController::$aCoreTables['settings']->delete(['settings_key'=>'store-server-url']);

            # Save API Key
            CoreEntityController::$aCoreTables['settings']->insert([
                'settings_key'=>'license-server-apikey',
                'settings_value'=>$sApiKey,
            ]);

            # Save API Secret Token
            CoreEntityController::$aCoreTables['settings']->insert([
                'settings_key'=>'license-server-apitoken',
                'settings_value'=>$sApiToken,
            ]);

            # Save API Server
            CoreEntityController::$aCoreTables['settings']->insert([
                'settings_key'=>'license-server-url',
                'settings_value'=>$sApiServer,
            ]);

            # Save Store API Key
            CoreEntityController::$aCoreTables['settings']->insert([
                'settings_key'=>'store-server-apikey',
                'settings_value'=>$sApiKey,
            ]);

            # Save Store API Secret Token
            CoreEntityController::$aCoreTables['settings']->insert([
                'settings_key'=>'store-server-apitoken',
                'settings_value'=>$sApiToken,
            ]);

            # Save Store API Server
            CoreEntityController::$aCoreTables['settings']->insert([
                'settings_key'=>'store-server-url',
                'settings_value'=>$sApiStore,
            ]);

            $this->flashMessenger()->addSuccessMessage('You are now connected to onep.lc');
            return $this->redirect()->toRoute('home');
        }

        # You can just use the default function and customize it via hooks
        # or replace the entire function if you need more customization
        $iInstanceID = CoreEntityController::$oSession->aLicences['info-instance-id'];
        if($iInstanceID == 0 || empty($iInstanceID)) {
            return new ViewModel([
                'oInstance'=>false,
                'oLicResponse'=>false,
            ]);
        }
        $sApiURL = CoreEntityController::$aGlobalSettings['license-server-url'].'/instance/api/get/'.$iInstanceID.'?authkey='.CoreEntityController::$aGlobalSettings['license-server-apikey'].'&authtoken='.CoreEntityController::$aGlobalSettings['license-server-apitoken'];
        $sAnswer = file_get_contents($sApiURL);

        $oResponse = json_decode($sAnswer);

        if(is_object($oResponse)) {
            if($oResponse->state == 'success') {
                $sLicApiURL = CoreEntityController::$aGlobalSettings['license-server-url'].'/license/api/list/'.$iInstanceID.'?authkey='.CoreEntityController::$aGlobalSettings['license-server-apikey'].'&authtoken='.CoreEntityController::$aGlobalSettings['license-server-apitoken'].'&modulename=all&systemkey='.CoreEntityController::$aGlobalSettings['license-server-apikey'];
                $sLicAnswer = file_get_contents($sLicApiURL);

                $oLicResponse = json_decode($sLicAnswer);

                return new ViewModel([
                    'oInstance'=>$oResponse->oItem,
                    'oLicResponse'=>$oLicResponse,
                ]);
            }
        }

        echo 'error while connecting to you onePlace account at'.$sApiURL;

        return false;
    }

    /**
     * Account Add Form
     *
     * @since 1.0.0
     * @return ViewModel - View Object with Data from Controller
     */
    public function addAction() {
        /**
         * You can just use the default function and customize it via hooks
         * or replace the entire function if you need more customization
         *
         * Hooks available:
         *
         * account-add-before (before show add form)
         * account-add-before-save (before save)
         * account-add-after-save (after save)
         */
        return $this->generateAddView('account');
    }

    /**
     * Account Edit Form
     *
     * @since 1.0.0
     * @return ViewModel - View Object with Data from Controller
     */
    public function editAction() {
        /**
         * You can just use the default function and customize it via hooks
         * or replace the entire function if you need more customization
         *
         * Hooks available:
         *
         * account-edit-before (before show edit form)
         * account-edit-before-save (before save)
         * account-edit-after-save (after save)
         */
        return $this->generateEditView('account');
    }

    /**
     * Account View Form
     *
     * @since 1.0.0
     * @return ViewModel - View Object with Data from Controller
     */
    public function viewAction() {
        /**
         * You can just use the default function and customize it via hooks
         * or replace the entire function if you need more customization
         *
         * Hooks available:
         *
         * account-view-before
         */
        return $this->generateViewView('account');
    }

    public function settingsAction() {
        # Set Layout based on users theme
        $this->setThemeBasedLayout('account');

        if(isset($_FILES['accountlogo'])) {
            move_uploaded_file($_FILES['accountlogo']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/img/logo.png');
        }

        if(isset($_FILES['accountfavicon'])) {
            move_uploaded_file($_FILES['accountfavicon']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/img/favicon.ico');
        }

        if(isset($_REQUEST['plc_account_title'])) {
            CoreEntityController::$aCoreTables['settings']->update([
                'settings_value'=>$_REQUEST['plc_account_title']
            ],'settings_key = \'app-title\'');
        }

        return false;
    }
}
