<?php

namespace Arkade\Demandware\Parsers;

use Carbon\Carbon;
use Arkade\Demandware\Entities;

class CredentialsParser
{
    /**
     * Parse the given array to a Credentials entity.
     *
     * @param  array $payload
     * @return Credentials
     */
    public function parse($payload)
    {
        $credentials = new Entities\Credentials();

        $enabled = false;
        if(!empty($payload->enabled)){
            $enabled = $payload->enabled;
        }
        $credentials->setEnabled($enabled);

        $locked = false;
        if(!empty($payload->enabled)){
            $locked = $payload->enabled;
        }
        $credentials->setLocked($locked);

        if(!empty($payload->login)){
            $credentials->setLogin($payload->login);
        }

        if(!empty($payload->password_question)){
            $credentials->setPasswordQuestion($payload->password_question);
        }

        return $credentials;
    }
}