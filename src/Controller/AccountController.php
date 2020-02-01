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

        # You can just use the default function and customize it via hooks
        # or replace the entire function if you need more customization
        return $this->generateIndexView('account');
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
}
