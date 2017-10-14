<?php

namespace LocationsBundle\Client\Builder;

use LocationsBundle\Client\Exceptions\GenerateLocationsException;
use LocationsBundle\Entity\Location;

class LocationsBuilder
{

    /**
     * @param string $locationsData
     * @return Location[]
     * @throws GenerateLocationsException
     */
    public function createLocationsFromJsonString(string $locationsData): array
    {
        try {
            $locations = \json_decode($locationsData, true);
            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new \InvalidArgumentException(
                    'json_decode error: ' . json_last_error_msg());
            }

            return $this->createLocationsFromArray($locations);

        } catch (GenerateLocationsException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            throw new GenerateLocationsException($exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @param array $locations
     * @return Location[]
     * @throws GenerateLocationsException
     */
    public function createLocationsFromArray(array $locations): array
    {
        try {
            $result = [];
            foreach ($locations as $location) {
                if (!isset($location['name'], $location['coordinates']['lat'], $location['coordinates']['long'])) {
                    throw new GenerateLocationsException(sprintf('Can\'t create Location. Not all parameters specified: "%s"', json_encode($location)));
                }
                $result[] = new Location($location['name'], $location['coordinates']['lat'], $location['coordinates']['long']);
            }

            return $result;
        } catch (GenerateLocationsException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            throw new GenerateLocationsException($exception->getMessage(), 0, $exception);
        }
    }
}