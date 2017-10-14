<?php

namespace LocationsBundle\Entity;

class Location
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    public function __construct(string $name, float $latitude, float $longitude)
    {
        $this->name = $name;
        if (abs($latitude) > 90) {
            throw new \InvalidArgumentException(sprintf('Invalid value for latitude %s, expected value: min/max -90/+90', $latitude));
        }
        if (abs($longitude) > 180) {
            throw new \InvalidArgumentException(sprintf('Invalid value for longitude %s, expected value:  min/max -180/+180', $longitude));
        }
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }
}