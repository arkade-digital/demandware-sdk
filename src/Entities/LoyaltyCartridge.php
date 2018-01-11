<?php

namespace Arkade\Demandware\Entities;

class LoyaltyCartridge extends AbstractEntity
{
    /**
     * @var float
     */
    protected $currentRewardBalance;

    /**
     * @var Carbon
     */
    protected $currentTierMaintainDeadline;

    /**
     * @var string
     */
    protected $currentTierOfMember;

    /**
     * @var Carbon
     */
    protected $nextTierMaintainDeadline;

    /**
     * @var string
     */
    protected $nextTierOfMember;

    /**
     * @var string
     */
    protected $nextTierSpendProgress;

    /**
     * @var string
     */
    protected $nextTierSpendRequired;

    /**
     * @var string
     */
    protected $omneoMemberID;

    /**
     * @var string
     */
    protected $omneoResourceID;

    /**
     * @var string
     */
    protected $prevTierOfMember;

    /**
     * @var string
     */
    protected $spendProgress;

    /**
     * @var string
     */
    protected $spendRequired;

    /**
     * @return float
     */
    public function getCurrentRewardBalance()
    {
        return $this->currentRewardBalance;
    }

    /**
     * @param float $currentRewardBalance
     * @return LoyaltyCartridge
     */
    public function setCurrentRewardBalance($currentRewardBalance)
    {
        $this->currentRewardBalance = $currentRewardBalance;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCurrentTierMaintainDeadline()
    {
        return $this->currentTierMaintainDeadline;
    }

    /**
     * @param Carbon $currentTierMaintainDeadline
     * @return LoyaltyCartridge
     */
    public function setCurrentTierMaintainDeadline(Carbon $currentTierMaintainDeadline = null)
    {
        $this->currentTierMaintainDeadline = $currentTierMaintainDeadline;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentTierOfMember()
    {
        return $this->currentTierOfMember;
    }

    /**
     * @param string $currentTierOfMember
     * @return LoyaltyCartridge
     */
    public function setCurrentTierOfMember($currentTierOfMember)
    {
        $this->currentTierOfMember = $currentTierOfMember;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getNextTierMaintainDeadline()
    {
        return $this->nextTierMaintainDeadline;
    }

    /**
     * @param Carbon $nextTierMaintainDeadline
     * @return LoyaltyCartridge
     */
    public function setNextTierMaintainDeadline(Carbon $nextTierMaintainDeadline = null)
    {
        $this->nextTierMaintainDeadline = $nextTierMaintainDeadline;
        return $this;
    }

    /**
     * @return string
     */
    public function getNextTierOfMember()
    {
        return $this->nextTierOfMember;
    }

    /**
     * @param string $nextTierOfMember
     * @return LoyaltyCartridge
     */
    public function setNextTierOfMember($nextTierOfMember)
    {
        $this->nextTierOfMember = $nextTierOfMember;
        return $this;
    }

    /**
     * @return string
     */
    public function getNextTierSpendProgress()
    {
        return $this->nextTierSpendProgress;
    }

    /**
     * @param string $nextTierSpendProgress
     * @return LoyaltyCartridge
     */
    public function setNextTierSpendProgress($nextTierSpendProgress)
    {
        $this->nextTierSpendProgress = $nextTierSpendProgress;
        return $this;
    }

    /**
     * @return string
     */
    public function getNextTierSpendRequired()
    {
        return $this->nextTierSpendRequired;
    }

    /**
     * @param string $nextTierSpendRequired
     * @return LoyaltyCartridge
     */
    public function setNextTierSpendRequired($nextTierSpendRequired)
    {
        $this->nextTierSpendRequired = $nextTierSpendRequired;
        return $this;
    }

    /**
     * @return string
     */
    public function getOmneoMemberID()
    {
        return $this->omneoMemberID;
    }

    /**
     * @param string $omneoMemberID
     * @return LoyaltyCartridge
     */
    public function setOmneoMemberID($omneoMemberID)
    {
        $this->omneoMemberID = $omneoMemberID;
        return $this;
    }

    /**
     * @return string
     */
    public function getOmneoResourceID()
    {
        return $this->omneoResourceID;
    }

    /**
     * @param string $omneoResourceID
     * @return LoyaltyCartridge
     */
    public function setOmneoResourceID($omneoResourceID)
    {
        $this->omneoResourceID = $omneoResourceID;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrevTierOfMember()
    {
        return $this->prevTierOfMember;
    }

    /**
     * @param string $prevTierOfMember
     * @return LoyaltyCartridge
     */
    public function setPrevTierOfMember($prevTierOfMember)
    {
        $this->prevTierOfMember = $prevTierOfMember;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpendProgress()
    {
        return $this->spendProgress;
    }

    /**
     * @param string $spendProgress
     * @return LoyaltyCartridge
     */
    public function setSpendProgress($spendProgress)
    {
        $this->spendProgress = $spendProgress;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpendRequired()
    {
        return $this->spendRequired;
    }

    /**
     * @param string $spendRequired
     * @return LoyaltyCartridge
     */
    public function setSpendRequired($spendRequired)
    {
        $this->spendRequired = $spendRequired;
        return $this;
    }

}
