<?php namespace App\Repositories;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InstagramRepository
 * @package App\Repositories
 */
class InstagramRepository
{
    const URL_MEDIA = 'https://www.instagram.com/%s/media/';
    const URL_USER = 'https://www.instagram.com/%s/?__a=1';

    const USER_NOT_FOUND = 'The user wasn`t found.';
    const ITEMS_NOT_FOUND = 'The items wasn`t found.';

    /**
     * The http client.
     *
     * @var Client
     */
    protected $httpClient;

    /**
     * InstagramRepository constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->httpClient = $client;
    }

    /**
     * Fetch the media items.
     *
     * @param string $user
     * @return mixed
     * @throws \Exception
     */
    public function getItems(string $user)
    {
        $request = $this->doRequest(
            sprintf(self::URL_MEDIA, $user),
            self::ITEMS_NOT_FOUND
        );
        return $request->items;
    }

    /**
     * Get user data.
     *
     * @param string $user
     * @return mixed
     * @throws \Exception
     */
    public function getUser(string $user)
    {
        $request = $this->doRequest(
            sprintf(self::URL_USER, $user),
            self::USER_NOT_FOUND
        );
        return $request->user;
    }

    /**
     * @param string $url
     * @param string $message
     * @return mixed
     * @throws \Exception
     */
    protected function doRequest(string $url = '', string $message = '')
    {
        $response = $this->httpClient->request('GET', $url, ['exceptions' => false]);

        if ($response->getStatusCode() == Response::HTTP_NOT_FOUND) {
            throw new \Exception($message);
        }

        return json_decode((string) $response->getBody());
    }
}