<?php

namespace LocationsBundle\Controller;

use LocationsBundle\Client\Clients\LocationsCurlClient;
use LocationsBundle\Client\Interfaces\LocationsClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;

class LocationsController extends Controller
{

    /**
     * @var LocationsClientInterface|LocationsCurlClient
     */
    private $locationsClient;

    public function __construct(LocationsClientInterface $locationsClient)
    {
        $this->locationsClient = $locationsClient;
    }

    /**
     * @Route("/locations", name="locations")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $locations = $this->locationsClient->getLocations();

        return new Response(VarDumper::dump($locations));
    }

    /**
     * @Route("/locations/test", name="locations.test")
     */
    public function testAction()
    {
        return $this->json([
            'success' => true,
            'data'    => [
                'locations' => [
                    [
                        'name'        => 'Eiffel Tower',
                        'coordinates' => [
                            'lat'  => 21.120000000000001,
                            'long' => 19.559999999999999,
                        ],
                    ],
                    [
                        'name'        => 'Eiffel 3Tower',
                        'coordinates' => [
                            'lat'  => 21.133199999999999,
                            'long' => 49.52600000000001,
                        ],
                    ],                    [
                        'name'        => 'Eiffel Tower',
                        'coord3inates' => [
                            'lat'  => 21.120000000000001,
                            'long' => 19.559999999999999,
                        ],
                    ],
                    [
                        'name'        => 'Eiffel 3Tower',
                        'coordinates' => [
                            'lat'  => 21.133199999999999,
                            'long' => 49.52600000000001,
                        ],
                    ],                    [
                        'name'        => 'Eiffel Tower',
                        'coordinates' => [
                            'lat'  => 21.120000000000001,
                            'long' => 19.559999999999999,
                        ],
                    ],
                    [
                        'name'        => 'Eiffel 3Tower',
                        'coordinates' => [
                            'lat'  => 21.133199999999999,
                            'long' => 49.52600000000001,
                        ],
                    ],                    [
                        'name'        => 'Eiffel Tower',
                        'coordinates' => [
                            'lat'  => 21.120000000000001,
                            'long' => 19.559999999999999,
                        ],
                    ],
                    [
                        'name'        => 'Eiffel 3Tower',
                        'coordinates' => [
                            'lat'  => 21.133199999999999,
                            'long' => 49.52600000000001,
                        ],
                    ],
                ],
            ],
        ]);
    }
}
