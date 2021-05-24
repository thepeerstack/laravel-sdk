<?php

namespace Thepeer\Sdk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Thepeer\Sdk\Exceptions\InvalidPayloadException;
use Thepeer\Sdk\Exceptions\InvalidReceiptException;
use Thepeer\Sdk\Exceptions\InvalidSecretKeyException;
use Thepeer\Sdk\Exceptions\InvalidSignatureException;
use Thepeer\Sdk\Exceptions\SeverErrorException;
use Thepeer\Sdk\Exceptions\UserNotFoundException;

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

        throw new InvalidSignatureException("signature does not match");
    }

    public function getReceipt($receipt)
    {
        try {
            $request = $this->client->get("/verify/{$receipt}");

            $payload = json_decode($request->getBody()->getContents());

            if ($request->getStatusCode() === 401) {
                throw new InvalidSecretKeyException($payload->message);
            } else if ($request->getStatusCode() === 404) {
                throw new InvalidReceiptException($payload->message);
            } else if ($request->getStatusCode() === 200) {
                return $payload;
            }

            throw new SeverErrorException("something went wrong");
        } catch (GuzzleException $e) {
            throw new SeverErrorException($e->getMessage());
        }
    }

    public function processReceipt($receipt)
    {
        try {
            $request = $this->client->post("/send/{$receipt}");

            if ($request->getStatusCode() === 401) {
                throw new InvalidSecretKeyException("invalid secret key");
            } else if ($request->getStatusCode() === 404) {
                throw new InvalidReceiptException("invalid receipt");
            } else if ($request->getStatusCode() === 200) {
                return json_decode($request->getBody()->getContents());
            }

            throw new SeverErrorException("something went wrong");
        } catch (GuzzleException $e) {
            throw new SeverErrorException($e->getMessage());
        }
    }

    public function indexUser(string $name, string $email, string $identifier)
    {
        try {
            $request = $this->client->post("/users/index", [
                "body" => json_encode([
                    'name' => $name,
                    'email' => $email,
                    'identifier' => $identifier
                ])
            ]);

            $payload = json_decode($request->getBody()->getContents());

            if ($request->getStatusCode() === 401) {
                throw new InvalidSecretKeyException($payload->message);
            } else if ($request->getStatusCode() === 406) {
                throw new InvalidPayloadException($payload->message);
            } else if ($request->getStatusCode() === 422) {
                foreach ($payload->errors as $error) {
                    throw new InvalidPayloadException($error[0]);
                }
            } else if ($request->getStatusCode() === 200) {
                return $payload;
            }

            throw new SeverErrorException("something went wrong");
        } catch (GuzzleException $e) {
            throw new SeverErrorException($e->getMessage());
        }
    }

    public function updateUser(string $reference, string $identifier)
    {
        try {
            $request = $this->client->put("/users/update/{$reference}", [
                "body" => json_encode([
                    'identifier' => $identifier
                ])
            ]);

            $payload = json_decode($request->getBody()->getContents());

            if ($request->getStatusCode() === 401) {
                throw new InvalidSecretKeyException("invalid secret key");
            } else if ($request->getStatusCode() === 406) {
                throw new InvalidPayloadException($payload->message);
            } else if ($request->getStatusCode() === 404) {
                throw new UserNotFoundException($payload->message);
            } else if ($request->getStatusCode() === 422) {
                foreach ($payload->errors as $error) {
                    throw new InvalidPayloadException($error[0]);
                }
            } else if ($request->getStatusCode() === 200) {
                return $payload;
            }

            throw new SeverErrorException("something went wrong");
        } catch (GuzzleException $e) {
            throw new SeverErrorException($e->getMessage());
        }
    }

    public function deleteUser(string $reference)
    {
        try {
            $request = $this->client->delete("/users/delete/{$reference}");

            $payload = json_decode($request->getBody()->getContents());

            if ($request->getStatusCode() === 401) {
                throw new InvalidSecretKeyException("invalid secret key");
            } else if ($request->getStatusCode() === 406) {
                throw new InvalidPayloadException($payload->message);
            } else if ($request->getStatusCode() === 404) {
                throw new UserNotFoundException($payload->message);
            } else if ($request->getStatusCode() === 422) {
                foreach ($payload->errors as $error) {
                    throw new InvalidPayloadException($error[0]);
                }
            } else if ($request->getStatusCode() === 200) {
                return true;
            }

            throw new SeverErrorException("something went wrong");
        } catch (GuzzleException $e) {
            throw new SeverErrorException($e->getMessage());
        }
    }
}