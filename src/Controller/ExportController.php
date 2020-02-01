<?php
/**
 * ExportController.php - Account Export Controller
 *
 * Main Controller for Account Export
 *
 * @category Controller
 * @package Account
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.5
 */

namespace OnePlace\Account\Controller;

use Application\Controller\CoreController;
use Application\Controller\CoreExportController;
use OnePlace\Account\Model\AccountTable;
use Laminas\Db\Sql\Where;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\View\Model\ViewModel;


class ExportController extends CoreExportController
{
    /**
     * ApiController constructor.
     *
     * @param AdapterInterface $oDbAdapter
     * @param AccountTable $oTableGateway
     * @since 1.0.0
     */
    public function __construct(AdapterInterface $oDbAdapter,AccountTable $oTableGateway,$oServiceManager) {
        parent::__construct($oDbAdapter,$oTableGateway,$oServiceManager);
    }


    /**
     * Dump Accounts to excel file
     *
     * @return ViewModel
     * @since 1.0.5
     */
    public function dumpAction() {
        $this->layout('layout/json');

        # Use Default export function
        $aViewData = $this->exportAccountBasedData('Accounts','account');

        # return data to view (popup)
        return new ViewModel($aViewData);
    }
}