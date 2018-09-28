<?php

namespace tmcsolution\messagingserviceclient;

/**
 * Статус сущности.
 *
 * @package tmcsolution\messagingserviceclient
 */
class Status
{
    private $_code;
    private $_message;

    /**
     * Код статуса.
     *
     * @return int
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * Описание статуса.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * Создаёт статус из массива данных.
     *
     * @param $data array Массив данных, полученных в результате парсинга JSON-ответа сервиса.
     */
    public function __construct($data)
    {
        $this->_code    = $data['code'];
        $this->_message = $data['message'];
    }
}