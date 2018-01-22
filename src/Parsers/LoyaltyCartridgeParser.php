<?php

namespace Arkade\Demandware\Parsers;

use Carbon\Carbon;
use Arkade\Demandware\Entities;

class LoyaltyCartridgeParser
{
    /**
     * Parse the given array to a Credentials entity.
     *
     * @param  array $payload
     * @return Credentials
     */
    public function parse($payload)
    {
        $loyaltyCartridge = new Entities\LoyaltyCartridge();

        if(!empty($payload->c_currentRewardBalance)){
            $loyaltyCartridge->setCurrentRewardBalance($payload->c_currentRewardBalance);
        }

        if(!empty($payload->c_currentTierMaintainDeadline)){
            $loyaltyCartridge->setCurrentTierMaintainDeadline(Carbon::parse((string) $payload->c_currentTierMaintainDeadline));
        }

        if(!empty($payload->c_currentTierOfMember)){
            $loyaltyCartridge->setCurrentTierOfMember($payload->c_currentTierOfMember);
        }

        if(!empty($payload->c_nextTierMaintainDeadline)){
            $loyaltyCartridge->setNextTierMaintainDeadline(Carbon::parse((string) $payload->c_nextTierMaintainDeadline));
        }

        if(!empty($payload->c_nextTierOfMember)){
            $loyaltyCartridge->setNextTierOfMember($payload->c_nextTierOfMember);
        }

        if(!empty($payload->c_nextTierSpendProgress)){
            $loyaltyCartridge->setNextTierSpendProgress($payload->c_nextTierSpendProgress);
        }

        if(!empty($payload->c_nextTierSpendRequired)){
            $loyaltyCartridge->setNextTierSpendRequired($payload->c_nextTierSpendRequired);
        }

        if(!empty($payload->c_omneoMemberID)){
            $loyaltyCartridge->setOmneoMemberID($payload->c_omneoMemberID);
        }

        if(!empty($payload->c_omneoResourceID)){
            $loyaltyCartridge->setOmneoResourceID($payload->c_omneoResourceID);
        }

        if(!empty($payload->c_prevTierOfMember)){
            $loyaltyCartridge->setPrevTierOfMember($payload->c_prevTierOfMember);
        }

        if(!empty($payload->c_spendProgress)){
            $loyaltyCartridge->setSpendProgress($payload->c_spendProgress);
        }

        if(!empty($payload->c_spendRequired)){
            $loyaltyCartridge->setSpendRequired($payload->c_spendRequired);
        }

        return $loyaltyCartridge;
    }
}