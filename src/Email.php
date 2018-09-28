<?php

namespace tmcsolution\messagingserviceclient;

/**
 * Сообщение электронной почты.
 *
 * @package tmcsolution\messagingserviceclient
 */
class Email
{
    use Base, Gatewayable, Prioritizable, Statusable;

    /**
     * @var string[] Адрес отправителя в виде массива ["Василий", "vasya@ya.ru"] или ["vasya@ya.ru"].
     */
    public $from;

    /**
     * @var string[] Адрес получателя в виде массива ["Василий", "vasya@ya.ru"] или ["vasya@ya.ru"].
     */
    public $to;

    /**
     * @var string Тема сообщения.
     */
    public $title;

    /**
     * @var string Текстовая часть сообщения.
     */
    public $body;

    /**
     * @var string Формат текстовой части: "plain" или "html".
     */
    public $format;

    private $_id;
    private $_sentVia;
    private $_token;

    /**
     * Идентификатор EMail-сообщения.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Название почтового сервера, через который отправлено сообщение.
     *
     * @return string|null
     */
    public function getSentVia()
    {
        return $this->_sentVia;
    }

    /**
     * Токен сообщения для отслеживания прочтения.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * Создаёт EMail-сообщение из массива данных.
     *
     * @param $data array Массив данных, полученных в результате парсинга JSON-ответа сервиса.
     */
    public function __construct($data)
    {
        $this->from   = $data['from'];
        $this->to     = $data['to'];
        $this->title  = $data['title'];
        $this->body   = $data['body'];
        $this->format = $data['format'];

        $this->_id      = $data['id'] ?? null;
        $this->_sentVia = $data['sentVia'] ?? null;
        $this->_token   = $data['token'] ?? null;

        $this->assignGatewayable($data);
        $this->assignPrioritizable($data);
        $this->assignStatusable($data);
    }
}