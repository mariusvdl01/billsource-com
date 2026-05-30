<?php
/**
 * Created by PhpStorm.
 * User: kenny
 * Date: 3/27/17
 * Time: 10:20 PM
 */

namespace console\models;

use common\models\ecosystem\BusinessEcosystem;

class Ecosystem
{
    protected $buyers;
    protected $consumers;
    protected $buyersBills;
    protected $suppliersBills;
    protected $consumersBills;
    protected $batch = array();
    protected $adjacentEcosystems;

    public function crunchData()
    {
        $this->buyers = BusinessEcosystem::findNumberOfBuyers();
        $this->consumers = BusinessEcosystem::findNumberOfConsumers();
        $this->buyersBills = BusinessEcosystem::findAllBillsForBuyers();
        $this->suppliersBills = BusinessEcosystem::findAllBillsForSuppliers();
        $this->consumersBills = BusinessEcosystem::findAllBillsForConsumers();
        $this->adjacentEcosystems = BusinessEcosystem::findAdjacentEcosystems();

        if ($this->buyersBills)
            $this->crunchBuyersBillsData();

        if ($this->suppliersBills)
            $this->crunchSuppliersBillsData();

        if ($this->consumersBills)
            $this->crunchConsumersBIllsData();

        if ($this->adjacentEcosystems)
            $this->crunchAdjacentEcosystemsData();

        $this->crunchBuyersData();
        $this->crunchConsumersData();
        $this->crunchEcosystemHealthData();
        $this->crunchEcosystemMultipliers();
    }

    protected function crunchBuyersBillsData()
    {
        $ecosystem = null;
        $this->batch = array();

        foreach ($this->buyersBills as $buyerBill) {
            $ecosystem = BusinessEcosystem::findOne(['business_id' => $buyerBill['business_id']]);
            if (null === $ecosystem) {
                $ecosystem = new BusinessEcosystem();
            }

            $ecosystem->setAttributes($buyerBill);
            $ecosystem->growth_factor = BusinessEcosystem::GROWTH_POTENTIAL_FACTOR;
            $ecosystem->save();
        }
    }

    protected function crunchSuppliersBillsData()
    {
        $ecosystem = null;
        $this->batch = array();

        foreach ($this->suppliersBills as $supplierBill) {
            $ecosystem = BusinessEcosystem::findOne(['business_id' => $supplierBill['id']]);
            if (null === $ecosystem) {
                $ecosystem = new BusinessEcosystem();
            }

            $ecosystem->setAttributes($supplierBill);
            $ecosystem->growth_factor = BusinessEcosystem::GROWTH_POTENTIAL_FACTOR;
            $ecosystem->save();
        }
    }

    protected function crunchConsumersBillsData()
    {
        $ecosystem = null;
        $this->batch = array();

        foreach ($this->consumersBills as $consumerBill) {
            $ecosystem = BusinessEcosystem::findOne(['business_id' => $consumerBill['business_id']]);
            if (null === $ecosystem) {
                $ecosystem = new BusinessEcosystem();
            }

            $ecosystem->setAttributes($consumerBill);
            $ecosystem->growth_factor = BusinessEcosystem::GROWTH_POTENTIAL_FACTOR;
            $ecosystem->save();
        }
    }

    protected function crunchBuyersData()
    {
        $ecosystem = null;
        $this->batch = array();

        foreach ($this->buyers as $buyer) {
            $ecosystem = BusinessEcosystem::findOne(['business_id' => $buyer['business_id']]);
            if (null === $ecosystem) {
                $ecosystem = new BusinessEcosystem();
            }

            $ecosystem->setAttributes($buyer);
            $ecosystem->save();
        }
    }

    protected function crunchConsumersData()
    {
        $ecosystem = null;
        $this->batch = array();

        foreach ($this->consumers as $consumer) {
            $ecosystem = BusinessEcosystem::findOne(['business_id' => $consumer['business_id']]);
            if (null === $ecosystem) {
                $ecosystem = new BusinessEcosystem();
            }

            $ecosystem->setAttributes($consumer);
            $ecosystem->save();
        }
    }

    protected function crunchAdjacentEcosystemsData()
    {
        $ecosystem = null;
        $this->batch = array();

        foreach ($this->adjacentEcosystems as $adjacentEcosystem) {
            $ecosystem = BusinessEcosystem::findOne(['business_id' => $adjacentEcosystem['business_id']]);
            if (null === $ecosystem) {
                $ecosystem = new BusinessEcosystem();
            }

            $ecosystem->setAttributes($adjacentEcosystem);
            $ecosystem->adjacent_ecosystem = $adjacentEcosystem['customers'] ** BusinessEcosystem::ADJACENT_ECOSYSTEM_FACTOR;
            $ecosystem->save();
        }
    }

    protected function crunchEcosystemMultipliers()
    {
        $ecosystems = BusinessEcosystem::find()->all();
        foreach ($ecosystems as $ecosystem) {
            $ecosystem->ecosystem_total = $ecosystem->buyers_total + $ecosystem->suppliers_total + $ecosystem->consumers_total;
            $ecosystem->growth_potential = $ecosystem->ecosystem_total * $ecosystem->growth_factor;
            $ecosystem->save();
        }
    }

    protected function crunchEcosystemHealthData()
    {

        $ecosystems = BusinessEcosystem::find()->all();
        if ($ecosystems) {
            foreach ($ecosystems as $ecosystem) {
                $ecosystem->ecosystem_health = 0;
                $creditors = $ecosystem->suppliers_total;
                $debtors = $ecosystem->buyers_total + $ecosystem->consumers_total;
                $cashflow = $debtors - $creditors;
                if ($cashflow > 0) {
                    $ecosystem->ecosystem_health = ceil(($creditors / $debtors) * 100);
                }
                $ecosystem->save();
            }
        }
    }
}