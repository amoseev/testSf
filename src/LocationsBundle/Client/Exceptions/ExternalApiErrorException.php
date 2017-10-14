<?php


namespace LocationsBundle\Client\Exceptions;

use Throwable;

class ExternalApiErrorException extends LocationsClientException
{
    /**
     * @var string
     */
    protected $externalApiCode;

    /**
     * @var string
     */
    protected $externalApiMessage;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getExternalApiCode()
    {
        return $this->externalApiCode;
    }

    /**
     * @param $externalApiCode
     * @return $this
     */
    public function setExternalApiCode(string $externalApiCode)
    {
        $this->externalApiCode = $externalApiCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getExternalApiMessage()
    {
        return $this->externalApiMessage;
    }

    /**
     * @param $externalApiMessage
     * @return $this
     */
    public function setExternalApiMessage(string $externalApiMessage)
    {
        $this->externalApiMessage = $externalApiMessage;

        return $this;
    }


    public static function createFromExternal(string $externalApiCode, string $externalApiMessage): ExternalApiErrorException
    {
        return (new static('External API error:'. $externalApiMessage))->setExternalApiCode($externalApiCode)->setExternalApiMessage($externalApiMessage);
    }


}