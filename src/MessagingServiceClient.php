<?php

namespace tmcsolution\messagingserviceclient;

use tmcsolution\messagingserviceclient\base\ApiClient;
use yii\base\InvalidConfigException;
use yii\base\InvalidValueException;
use yii\helpers\Json;
use yii\web\HttpException;

class MessagingServiceClient extends ApiClient
{
    /**
     * Возвращает массив сообщений.
     *
     * @param int[] $ids Массив идентификаторов сообщений, который необходимо получить.
     * @return Message[] Массив сообщений, который вернул сервис.
     * @throws InvalidConfigException Если клиент был неправильно сконфигурирован.
     * @throws HttpException Если сервис вернул ошибку.
     */
    public function getMessages($ids = [])
    {
        $url = 'message';

        if (!empty($ids)) {
            $url .= '?';

            foreach ($ids as $id) {
                $url = sprintf('%sids=%d&', $url, $id);
            }
            $url = rtrim($url, '&');
        }

        return self::messagesFromResponse($this->send('GET', $url));
    }

    /**
     * Отправляет запрос на создание сообщений.
     *
     * @param $messages Message[] Массив сообщений для создания.
     * @return Message[] Массив созданных сообщений, который вернул сервис.
     * @throws InvalidConfigException Если клиент был неправильно сконфигурирован.
     * @throws HttpException Если сервис вернул ошибку.
     */
    public function createMessages($messages)
    {
        $data = [];

        foreach ($messages as $message) {
            $data[] = $message->toArray();
        }

        return self::messagesFromResponse($this->send('POST', 'message', $data));
    }

    /**
     * Преобразует массив данных в массив моделей.
     *
     * @param $messages array Массив данных, который вернул сервис.
     * @return Message[] Массив сообщений, который вернул сервис.
     * @throws InvalidValueException Если не удалось сформировать модель из ответа сервиса.
     */
    protected static function messagesFromResponse($messages)
    {
        $result = [];

        foreach ($messages as $data) {
            $message = new Message(['scenario' => Message::SCENARIO_RESPONSE]);
            $message->load($data, '');

            if (!$message->validate()) {
                throw new InvalidValueException('Ошибка при формировании модели из ответа сервиса: ' .
                                                Json::encode($message->errors, 320 | JSON_PRETTY_PRINT));
            }
            $result[] = $message;
        }

        return $result;
    }
}