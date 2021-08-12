<?php

namespace Thepeer\Sdk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Thepeer\Sdk\Exceptions\ForbiddenException;
use Thepeer\Sdk\Exceptions\InvalidPayloadException;
use Thepeer\Sdk\Exceptions\InvalidResourceException;
use Thepeer\Sdk\Exceptions\NotAcceptableException;
use Thepeer\Sdk\Exceptions\ServiceUnavailableException;
use Thepeer\Sdk\Exceptions\UnauthorizedException;
use Thepeer\Sdk\Exceptions\ServerErrorException;

class Thepeer
{
    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var Client
     */
    private $client;

    /**
     * Thepeer constructor.
     * @param $secretKey
     */
    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
        $this->client = new Client([
            'base_uri' => 'https://api.thepeer.co',
            'headers' => [
                'x-api-key' => $secretKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            'http_errors' => false
        ]);
    }

    public function validateSignature(Request $payload)
    {
        $headerSignature = $payload->header('X-Thepeer-Signature');

        $calculatedSignature = hash_hmac('sha1', json_encode($payload->all()), $this->secretKey);

        if ($headerSignature === $calculatedSignature) {
            return true;
        }

        return false;
    }

    public function getSendReceipt(string $receipt)
    {
        try {
            $request = $this->client->get("/send/{$receipt}");

            $payload = json_decode($request->getBody()->getContents());

            return $this->processResponse($payload, $request);
        } catch (GuzzleException $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    public function processSendReceipt(string $receipt, bool $insufficient_funds)
    {
        try {
            $request = $this->client->post("/send/{$receipt}", [
                "body" => json_encode([
                    'insufficient_funds' => $insufficient_funds,
                ])
            ]);

            $payload = json_decode($request->getBody()->getContents());

            return $this->processResponse($payload, $request);
        } catch (GuzzleException $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    public function indexUser(string $name, string $email, string $identifier)
    {
        try {
            $request = $this->client->post("/users", [
                "body" => json_encode([
                    'name' => $name,
                    'email' => $email,
                    'identifier' => $identifier
                ])
            ]);

            $payload = json_decode($request->getBody()->getContents());

            return $this->processResponse($payload, $request);
        } catch (GuzzleException $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    public function updateUser(string $reference, string $identifier)
    {
        try {
            $request = $this->client->put("/users/{$reference}", [
                "body" => json_encode([
                    'identifier' => $identifier
                ])
            ]);

            $payload = json_decode($request->getBody()->getContents());

            return $this->processResponse($payload, $request);
        } catch (GuzzleException $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    public function deleteUser(string $reference)
    {
        try {
            $request = $this->client->delete("/users/{$reference}");

            $payload = json_decode($request->getBody()->getContents());

            return $this->processResponse($payload, $request);
        } catch (GuzzleException $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    public function getLink(string $link)
    {
        try {
            $request = $this->client->get("/link/{$link}");

            $payload = json_decode($request->getBody()->getContents());

            return $this->processResponse($payload, $request);
        } catch (GuzzleException $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    public function chargeLink(string $link, int $amount, string $remark)
    {
        try {
            $request = $this->client->post("/link/{$link}/charge", [
                "body" => json_encode([
                    'amount' => $amount,
                    'remark' => $remark
                ])
            ]);

            $payload = json_decode($request->getBody()->getContents());

            return $this->processResponse($payload, $request);
        } catch (GuzzleException $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    public function authorizaDirectCharge(string $reference, bool $insufficient_funds)
    {
        try {
            $request = $this->client->post("/debit/{$reference}", [
                "body" => json_encode([
                    'insufficient_funds' => $insufficient_funds,
                ])
            ]);

            $payload = json_decode($request->getBody()->getContents());

            return $this->processResponse($payload, $request);
        } catch (GuzzleException $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    private function processResponse($payload, $request)
    {
        switch ($request->getStatusCode()) {
            case 201:
            case 200:
                return $payload;
            case 401:
                throw new UnauthorizedException($payload->message);
            case 403:
                throw new ForbiddenException($payload->message);
            case 404:
                throw new InvalidResourceException($payload->message);
            case 422:
                foreach ($payload->errors as $error) {
                    throw new InvalidPayloadException($error[0]);
                }
            case 406:
                throw new NotAcceptableException($payload->message);
            case 503:
                throw new ServiceUnavailableException($payload->message);
            default:
                throw new ServerErrorException($payload->message);
        }
    }
}