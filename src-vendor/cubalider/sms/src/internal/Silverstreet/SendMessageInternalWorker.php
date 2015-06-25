<?php

namespace Cubalider\Sms\Silverstreet;

use Cubalider\Sms\GenerateNumberInternalWorker;
use Cubalider\Sms\InsufficientCreditInternalException;
use Cubalider\Sms\InvalidParameterInternalException;
use Cubalider\Sms\InvalidRequestInternalException;
use GuzzleHttp\ClientInterface;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class SendMessageInternalWorker
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var GenerateNumberInternalWorker
     */
    private $generateNumberInternalWorker;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param string                       $url
     * @param string                       $username
     * @param string                       $password
     * @param GenerateNumberInternalWorker $generateNumberInternalWorker
     * @param ClientInterface              $client
     * 
     * @Di\InjectParams({
     *     "url"                          = @Di\Inject("%cubalider.sms.silverstreet.url%"),
     *     "username"                     = @Di\Inject("%cubalider.sms.silverstreet.username%"),
     *     "password"                     = @Di\Inject("%cubalider.sms.silverstreet.password%"),
     *     "generateNumberInternalWorker" = @Di\Inject("cubalider.sms.generate_number_internal_worker"),
     *     "client"                       = @Di\Inject("cubalider.sms.client")
     * })
     */
    function __construct(
        $url,
        $username,
        $password,
        GenerateNumberInternalWorker $generateNumberInternalWorker,
        ClientInterface $client
    )
    {
        $this->url = $url;
        $this->username = $username;
        $this->password = $password;
        $this->generateNumberInternalWorker = $generateNumberInternalWorker;
        $this->client = $client;
    }

    /**
     * Sends a message.
     *
     * @param string $id
     * @param string $to
     * @param string $body
     *
     * @throws InvalidRequestInternalException     If the request is invalid.
     *                                             Maybe the api changed?
     * @throws InvalidParameterInternalException   If some parameter is invalid
     * @throws InsufficientCreditInternalException If credit is insufficient
     */
    public function send($id, $to, $body)
    {
        $to = str_replace('+', '', $to);

        $url = sprintf(
            "%s?username=%s&password=%s&destination=%s&sender=%s&body=%s&reference=%s&dlr=1",
            $this->url,
            $this->username,
            $this->password,
            $to,
            $this->generateNumberInternalWorker->generate(false),
            urlencode(mb_convert_encoding($body, 'ISO-8859-1', 'auto')),
            $id
        );

        $response = $this->client->get($url);

        if ($response->getBody() != '01') {
            switch ($response->getBody()) {
                case '100':
                    throw new InvalidRequestInternalException(sprintf(
                        "Operator returned: \"Parameter(s) are missing.\". Request url was:\r\n%s",
                        $url
                    ));
                case '110':
                    throw new InvalidRequestInternalException(sprintf(
                        "Operator returned: \"Bad combination of parameters.\". Request url was:\r\n%s",
                        $url
                    ));
                case '120':
                    throw new InvalidParameterInternalException();
                case '130':
                    throw new InsufficientCreditInternalException();
            }
        }
    }
}
