<?php
/*
 *  @author     The S Group <support@sashas.org>
 *  @copyright  2024 Endeavour Inc. (https://www.sashas.org)
 *  @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */
declare(strict_types=1);

namespace Razoyo\CarProfile\Model\CustomerCar;

use Magento\Framework\Api\AbstractSimpleObject;
use Razoyo\CarProfile\Api\Data\CustomerCarInterface;
use Razoyo\CarProfile\Model\CustomerCar as CustomerCarModel;

/**
 * Implementation of Customer Car interface
 * @deprecated
 */
class CustomerCar extends AbstractSimpleObject implements CustomerCarInterface
{
    /**
     * {@inheritdoc}
     */
    public function getCustomerId(): ?int
    {
        return $this->_get(static::CUSTOMER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerId(int $customerId): CustomerCarInterface
    {
        return $this->setData(static::CUSTOMER_ID, $customerId);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->_get(static::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id): CustomerCarInterface
    {
        return $this->setData(static::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getYear(): int
    {
        return $this->_get(static::YEAR);
    }

    /**
     * {@inheritdoc}
     */
    public function setYear(int $year): CustomerCarInterface
    {
        return $this->setData(static::YEAR, $year);
    }

    /**
     * {@inheritdoc}
     */
    public function getMake(): string
    {
        return $this->_get(static::MAKE);
    }

    /**
     * {@inheritdoc}
     */
    public function setMake(string $make): CustomerCarInterface
    {
        return $this->setData(static::MAKE, $make);
    }

    /**
     * {@inheritdoc}
     */
    public function getModel(): string
    {
        return $this->_get(static::MODEL);
    }

    /**
     * {@inheritdoc}
     */
    public function setModel(string $model): CustomerCarInterface
    {
        return $this->setData(static::MODEL, $model);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice(): float
    {
        return $this->_get(static::PRICE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPrice(float $price): CustomerCarInterface
    {
        return $this->setData(static::PRICE, $price);
    }

    /**
     * {@inheritdoc}
     */
    public function getSeats(): int
    {
        return $this->_get(static::SEATS);
    }

    /**
     * {@inheritdoc}
     */
    public function setSeats(int $seats): CustomerCarInterface
    {
        return $this->setData(static::SEATS, $seats);
    }

    /**
     * {@inheritdoc}
     */
    public function getMpg(): int
    {
        return $this->_get(static::MPG);
    }

    /**
     * {@inheritdoc}
     */
    public function setMpg(int $mpg): CustomerCarInterface
    {
        return $this->setData(static::MPG, $mpg);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage(): string
    {
        return $this->_get(static::IMAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setImage(string $image): CustomerCarInterface
    {
        return $this->setData(static::IMAGE, $image);
    }
}
