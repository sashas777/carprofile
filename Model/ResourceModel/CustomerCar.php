<?php
/*
 *  @author     The S Group <support@sashas.org>
 *  @copyright  2024 Endeavour Inc. (https://www.sashas.org)
 *  @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */
declare(strict_types=1);

namespace Razoyo\CarProfile\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Customer car model
 */
class CustomerCar extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('customer_car', 'entity_id');
    }
}
