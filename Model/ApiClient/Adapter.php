<?php
/*
 *  @author     The S Group <support@sashas.org>
 *  @copyright  2024 Endeavour Inc. (https://www.sashas.org)
 *  @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */
declare(strict_types=1);

namespace Razoyo\CarProfile\Model\ApiClient;

use Magento\Framework\Serialize\SerializerInterface;
use Laminas\Http\ClientFactory;
use Laminas\Http\Client;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Laminas\Http\Request;

/**
 * API Adapter for requests
 * For API documentation
 * @see https://editor.swagger.io/?url=https%3A%2F%2Fstorage.googleapis.com%2Frazoyo-exam-spec%2Fcars.yaml%3Fv%3D22.05.06
 */

class Adapter
{
    /**
     * Authentication token
     * @var string
     */
    private string $authToken = '';

    /**
     * Timestamp when token is valid
     * @var int
     */
    private int $authTokenValidTimestamp = 0;

    /**
     * Token header
     */
    private const API_AUTH_HEADER = 'your-token';

    /**
     * Token key in the api response
     */
    private const API_AUTH_EXPIRE_KEY = 'expires';


    /**
     * @param ClientFactory $clientFactory
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly ClientFactory $clientFactory,
        private readonly SerializerInterface $serializer,
        private readonly LoggerInterface $logger
    ) { }

    /**
     * @param $uri
     * @param $params
     * @param $method
     * @param $authRequired
     * @return array
     * @throws LocalizedException
     */
    public function execute($uri, $params = [], $method=Request::METHOD_GET, $authRequired = false): array
    {
        $this->logger->debug(__METHOD__.' '.$uri);
        $headers =  [
            'accept' => 'application/json'
        ];
        if ($authRequired) {
            $headers['Authorization'] = 'Bearer '.$this->getAuthToken();
        }
        /** @var Client $client */
        $client =  $this->clientFactory->create();
        $client->setHeaders($headers)
            ->setMethod($method)
            ->setUri($uri)
            ->setOptions([
                'maxredirects' => 0,
                'timeout'      => 30,
            ]);

        if ($params) {
            $client->setParameterGet($params);
        }

        try {
            $httpResponse = $client->send();
            $responseJson = $httpResponse->getBody();
            $responseArray = $this->serializer->unserialize($responseJson);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('There is an error during cars api request. Please try again.'));
        }

        if (!$httpResponse->isSuccess()) {
            $this->logger->error('The api request did not succeed.'.
                'response code: '.$httpResponse->getStatusCode().
                'response body: '.$responseJson
            );
            throw new LocalizedException(__(
                'There is an error in cars api response. Please try again.'
            ));
        }
        // when there was an error with un-serialization
        if ($responseArray === false) {
            $this->logger->error('The api response is not in json format.'.
                'response code: '.$httpResponse->getStatusCode().
                'response body: '.$responseJson
            );
            throw new LocalizedException(__(
                'There is an error in cars api response format. Please try again.'
            ));
        }

        /** Token updated with each request that has it in headers */
        /** @var bool|\Laminas\Http\Header\HeaderInterface $tokenHeader */
        if ( array_key_exists(static::API_AUTH_EXPIRE_KEY, $responseArray) &&
            $tokenHeader = $httpResponse->getHeaders()->get(name: static::API_AUTH_HEADER)
        ) {
           $this->setAuthToken($tokenHeader->getFieldValue());
           $this->setAuthTokenValidTimestamp($responseArray[static::API_AUTH_EXPIRE_KEY]);
        }

        return $responseArray;
    }

    /**
     * Validate existing token
     */
    public function isTokenValid(): bool
    {
        return ($this->getAuthTokenValidTimestamp()>time() ? true : false);
    }

    /**
     * @return string
     */
    protected function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @param string $token
     * @return $this
     */
    protected function setAuthToken(string $token): self
    {
        $this->authToken = $token;
        return $this;
    }

    /**
     * @return int
     */
    protected function getAuthTokenValidTimestamp(): int
    {
        return $this->authTokenValidTimestamp;
    }

    /**
     * @param int $tokenTimestamp
     * @return $this
     */
    protected function setAuthTokenValidTimestamp(int $tokenTimestamp): self
    {
        $this->authTokenValidTimestamp = $tokenTimestamp;
        return $this;
    }
}
