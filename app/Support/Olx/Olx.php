<?php

namespace App\Support\Olx;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use DOMDocument;
use DOMXPath;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class Olx
{
    public const API_HOST = 'https://www.olx.ua/d/uk/obyavlenie';

    /** @var string */
    private string $host;

    /** @var array */
    private array $headers;

    public function __construct()
    {
        $this->host = self::API_HOST;
        $this->headers = config('services.olx.headers');
    }

    /**
     * @param string $adPath
     * @return string|null
     * @throws GuzzleException
     */
    public function parseAdPriceByPath(string $adPath): ?string
    {
        $response = $this->request($adPath);

        if ($response->getStatusCode() === Response::HTTP_OK) {
            $html = $response->getBody();
            $dom = new DOMDocument;

            $dom->loadHTML($html, LIBXML_NOERROR);

            $xpath = new DOMXPath($dom);

            $priceNode = $xpath->query("//h3[@class='css-90xrc0']");
            if ($priceNode->length > 0) {

                return $priceNode->item(0)->nodeValue;
            }
        }

        return null;
    }

    /**
     * @param string $path
     * @return ResponseInterface
     * @throws GuzzleException
     * @throws \Exception
     */
    protected function request(string $path): ResponseInterface
    {
        $requestContent = [
            'headers' => $this->headers,
        ];

        try {
            return (new Client())->get($this->host . '/' . $path . '.html', $requestContent);

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            throw $e;
        }
    }
}
