<?php

namespace tmcsolution\messagingserviceclient\base;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\web\HttpException;

/**
 * WebAPI клиент для взаимодействия с сервисами, созданными на базе gustaf.
 *
 * @package tmcsolution
 */
class ApiClient extends Component
{
    /**
     * @var string|null Bearer-токен аутентификации/авторизации в WebAPI.
     */
    public $authToken = null;

    /**
     * @var string Базовый URL WebAPI-сервиса для отправки запросов.
     */
    public $baseUrl;

    /**
     * @var array Конфигурация запросов.
     * @see https://github.com/yiisoft/yii2-httpclient/blob/master/docs/guide-ru/basic-usage.md
     */
    public $requestConfig = [];

    /**
     * @var array Опции запросов.
     * @see https://github.com/yiisoft/yii2-httpclient/blob/master/docs/guide-ru/usage-request-options.md
     */
    public $requestOptions = [];

    /**
     * @var array Конфигурация ответов.
     * @see https://github.com/yiisoft/yii2-httpclient/blob/master/docs/guide-ru/basic-usage.md
     */
    public $responseConfig = [];

    /**
     * @var Client HTTP-клиент для отправки запросов.
     */
    private $_httpClient;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (empty($this->baseUrl)) {
            throw new InvalidConfigException('Не указан базовый URL WebAPI-сервиса.');
        }
    }

    /**
     * Создаёт и/или возвращает созданный HTTP-клиент.
     *
     * @return object|Client
     * @throws InvalidConfigException Если клиент был неправильно сконфигурирован.
     */
    public function getHttpClient()
    {
        if (!is_object($this->_httpClient)) {
            $this->_httpClient = Yii::createObject(
                [
                    'class'          => Client::class,
                    'baseUrl'        => $this->baseUrl,
                    'requestConfig'  => $this->requestConfig,
                    'responseConfig' => $this->responseConfig,
                ]
            );
        }
        return $this->_httpClient;
    }

    /**
     * Делает запрос к сервису и либо возвращает данные в виде массива, либо кидает Exception в случае ошибки.
     *
     * @param string $method  Метод запроса.
     * @param string $url     Относительный URL запроса.
     * @param array  $data    Массив данных для запроса.
     * @param array  $headers HTTP-заголовки запроса.
     * @param array  $options Массив опций для запроса. Если не пустой, заменяет глобально заданные опции 'requestOptions'.
     *
     * @return mixed Массив данных, представляющий собой ответ сервиса.
     * @throws HttpException Если сервис вернул ошибку.
     * @throws InvalidConfigException Если клиент был неправильно сконфигурирован.
     */
    public function send($method, $url, $data = [], $headers = [], $options = [])
    {
        $response = $this->getHttpClient()
                         ->createRequest()
                         ->setMethod($method)
                         ->setUrl($url)
                         ->setData($data)
                         ->addHeaders(['Authorization' => 'Bearer ' . $this->authToken])
                         ->addHeaders($headers)
                         ->setOptions(empty($options) ? $this->requestOptions : $options)
                         ->send();

        $statusCode = intval($response->statusCode);

        if ($statusCode >= 200 && $statusCode < 400) {
            return $response->data;
        }

        throw new HttpException($statusCode);
    }
}