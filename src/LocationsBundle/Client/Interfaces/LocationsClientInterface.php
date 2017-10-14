<?php

namespace LocationsBundle\Client\Interfaces;

use LocationsBundle\Client\Exceptions\LocationsClientException;
use LocationsBundle\Entity\Location;
use Psr\Log\LoggerAwareInterface;

interface LocationsClientInterface extends LoggerAwareInterface
{
    /**
     * @return Location[]
     * @throws LocationsClientException
     */
    public function getLocations(): array;
}