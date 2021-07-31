<?php
namespace Tests\Helpers;

use \Vohof\GuzzleClient;
use \Vohof\TransmissionResponseException;
use \Vohof\TransmissionBadJsonException;
use \Vohof\TransmissionSessionException;
use \Guzzle\Http\Exception\ClientErrorResponseException;

class TestGuzzleClient extends GuzzleClient {
  public function request($method, $params = array())
    {
        $this->lastRequest = func_get_args();
        try
        {
            $req = $this->client->post($this->endpoint, null, json_encode(array(
                'method' => $method,
                'arguments' => $params
            )), ["connect_timeout" => 2, "timeout" => 5]);

            $res = json_decode($req->send()->getBody(true), true);

            if (is_null($res))
            {
                throw new TransmissionBadJsonException('The response from RPC server is invalid.');
            }

            if ($res['result'] != 'success')
            {
                throw new TransmissionResponseException("The RPC server did not return a success result flag: ${res['result']}");
            }

            if ( ! isset($res['arguments']))
            {
                throw new TransmissionResponseException("The RPC server did not return any arguments.");
            }

            return $res['arguments'];
        }
        catch (ClientErrorResponseException $e)
        {
            $response = $e->getResponse();
            $errorCode = $response->getStatusCode();

            if ($errorCode == 409)
            {
                if ( ! $response->hasHeader('X-Transmission-Session-Id'))
                {
                    throw new TransmissionSessionException('No X-Transmission-Session-Id header found.');
                }

                $sessionId = $response->getHeader('X-Transmission-Session-Id');

                $this->client->setDefaultOption(
                    'headers/X-Transmission-Session-Id', $sessionId
                );

                $this->retries++;

                if ($this->retries > $this->maxRetries)
                {
                    throw new TransmissionSessionException('Transmission doesn\'t like our session Id.');
                }

                return call_user_func_array(
                    array($this, 'request'),
                    $this->lastRequest
                );
            }

            throw $e;
        }
    }
}