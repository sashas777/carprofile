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
 * Api repository for cars
 */
interface ApiCarsRepositoryInterface
{
    public function getCarMakes($forceUpdate = false): array;

    public function getCarsByMake(string $make): array;

    public function getCarById(string $carId): CustomerCarInterface;
}
