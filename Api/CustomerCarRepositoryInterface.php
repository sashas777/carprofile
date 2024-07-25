<?php
/*
 *  @author     The S Group <support@sashas.org>
 *  @copyright  2024 Endeavour Inc. (https://www.sashas.org)
 *  @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */
declare(strict_types=1);

namespace Razoyo\CarProfile\Api;

use Razoyo\CarProfile\Api\Data\CustomerCarInterface;

/**
 * Customer car extension attribute repository
 */
interface CustomerCarRepositoryInterface
{
    /**
     * @param CustomerCarInterface $customerCar
     * @return CustomerCarInterface
     */
    public function save(CustomerCarInterface $customerCar): CustomerCarInterface;

    /**
     * @param int $customerId
     * @return CustomerCarInterface
     */
    public function get(int $customerId): CustomerCarInterface;

    /**
     * @param CustomerCarInterface $customerCar
     * @return bool
     */
    public function delete(CustomerCarInterface $customerCar): bool;
}
