<?php

namespace LocationsBundle\Client\Clients;

use LocationsBundle\Client\Builder\LocationsBuilder;
use LocationsBundle\Client\Exceptions\ExternalApiErrorException;
use LocationsBundle\Client\Exceptions\LocationsClientException;
use LocationsBundle\Client\Exceptions\ReceiveDataException;
use LocationsBundle\Client\Interfaces\LocationsClientInterface;
use LocationsBundle\Entity\Location;
use Psr\Log\LoggerInterface;

class LocationsCurlClient implements LocationsClientInterface
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var array
     */
    private $options;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @var LocationsBuilder
     */
    private $locationsBuilder;

    public function __construct(string $url, array $options, LocationsBuilder $locationsBuilder)
    {
        $this->url = $url;
        $this->options = $options;
        $this->locationsBuilder = $locationsBuilder;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return Location[]
     * @throws LocationsClientException
     */
    public function getLocations(): array
    {
        try {
            $responseStr = $this->doRequest();
            $response = $this->getResponseArr($responseStr);
            $this->validateResponse($response);

            return $this->locationsBuilder->createLocationsFromArray($response['data']['locations']);
        } catch (LocationsClientException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            throw new LocationsClientException($exception->getMessage(), 0, $exception);
        }

    }

    /**
     * @return string
     * @throws LocationsClientException
     */
    private function doRequest(): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->options['connection_timeout_ms'] ?? 1000);

        $this->log('request', ['url' => $this->url]);

        $result = curl_exec($ch);
        if ($result === false) {
            $msg = curl_error($ch);
            curl_close($ch);
            $this->log('response', ['curl_error' => $msg]);
            throw new ReceiveDataException($msg);
        }
        curl_close($ch);
        $this->log('response', ['content' => $result]);

        return $result;
    }

    private function log(string $message, array $context)
    {
        if ($this->logger) {
            $this->logger->debug($message, $context);
        }
    }

    /**
     * @param array $response
     * @throws ExternalApiErrorException
     * @throws ReceiveDataException
     */
    private function validateResponse(array $response)
    {
        if (! isset($response['success'])) {
            throw new ReceiveDataException('Invalid receive data format, expected "success" section');
        }
        if ($response['success'] !== true) {
            if (isset($response['data']['code'], $response['data']['message'])) {
                throw ExternalApiErrorException::createFromExternal((int)$response['data']['code'], $response['data']['message']);
            }
            throw new ReceiveDataException('Invalid receive data format');
        }
        if (! isset($response['data']['locations'])) {
            throw new ReceiveDataException('Invalid receive data format, expected "locations" section');
        }
    }

    /**
     * @param string $str
     * @return array
     * @throws \InvalidArgumentException
     */
    private function getResponseArr(string $str): array
    {
        $response = \json_decode($str, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(
                'json_decode error: ' . json_last_error_msg());
        }

        return $response;
    }

}