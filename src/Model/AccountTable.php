<?php
/**
 * AccountTable.php - Account Table
 *
 * Table Model for Account
 *
 * @category Model
 * @package Account
 * @author Verein onePlace
 * @copyright (C) 2020 Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

namespace OnePlace\Account\Model;

use Application\Controller\CoreController;
use Application\Model\CoreEntityTable;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Paginator\Paginator;
use Laminas\Paginator\Adapter\DbSelect;

class AccountTable extends CoreEntityTable {

    /**
     * AccountTable constructor.
     *
     * @param TableGateway $tableGateway
     * @since 1.0.0
     */
    public function __construct(TableGateway $tableGateway) {
        parent::__construct($tableGateway);

        # Set Single Form Name
        $this->sSingleForm = 'account-single';
    }

    /**
     * Get Account Entity
     *
     * @param int $id
     * @return mixed
     * @since 1.0.0
     */
    public function getSingle($id) {
        # Use core function
        return $this->getSingleEntity($id,'Account_ID');
    }

    /**
     * Save Account Entity
     *
     * @param Account $oAccount
     * @return int Account ID
     * @since 1.0.0
     */
    public function saveSingle(Account $oAccount) {
        $aData = [
            'label' => $oAccount->label,
        ];

        $aData = $this->attachDynamicFields($aData,$oAccount);

        $id = (int) $oAccount->id;

        if ($id === 0) {
            # Add Metadata
            $aData['created_by'] = CoreController::$oSession->oUser->getID();
            $aData['created_date'] = date('Y-m-d H:i:s',time());
            $aData['modified_by'] = CoreController::$oSession->oUser->getID();
            $aData['modified_date'] = date('Y-m-d H:i:s',time());

            # Insert Account
            $this->oTableGateway->insert($aData);

            # Return ID
            return $this->oTableGateway->lastInsertValue;
        }

        # Check if Account Entity already exists
        try {
            $this->getSingle($id);
        } catch (\RuntimeException $e) {
            throw new \RuntimeException(sprintf(
                'Cannot update account with identifier %d; does not exist',
                $id
            ));
        }

        # Update Metadata
        $aData['modified_by'] = CoreController::$oSession->oUser->getID();
        $aData['modified_date'] = date('Y-m-d H:i:s',time());

        # Update Account
        $this->oTableGateway->update($aData, ['Account_ID' => $id]);

        return $id;
    }

    /**
     * Generate new single Entity
     *
     * @return Account
     * @since 1.0.7
     */
    public function generateNew() {
        return new Account($this->oTableGateway->getAdapter());
    }
}