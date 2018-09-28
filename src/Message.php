<?php

namespace tmcsolution\messagingserviceclient;

/**
 * Сообщение.
 *
 * @package tmcsolution\messagingserviceclient
 */
class Message
{
    use Base, Statusable;

    /**
     * @var Email|null Сообщение электронной почты.
     */
    public $email;

    /**
     * @var Sms|null СМС сообщение.
     */
    public $sms;

    /**
     * @var Telegram|null Сообщение Telegram.
     */
    public $telegram;

    /**
     * @var Viber|null Сообщение Viber.
     */
    public $viber;

    private $_id;
    private $_consumerId;
    private $_createdAt;

    /**
     * Идентификатор сообщения.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Идентификатор потребителя.
     *
     * @return int
     */
    public function getConsumerId()
    {
        return $this->_consumerId;
    }

    /**
     * Дата и время создания сообщения.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->_createdAt;
    }

    /**
     * Создаёт сообщение из массива данных.
     *
     * @param $data array Массив данных, полученных в результате парсинга JSON-ответа сервиса.
     */
    public function __construct($data)
    {
        $this->email    = new Email($data['email']);
        $this->sms      = new Sms($data['sms']);
        $this->telegram = new Telegram($data['telegram']);
        $this->viber    = new Viber($data['viber']);

        $this->_id         = $data['id'];
        $this->_consumerId = $data['consumerId'];
        $this->_createdAt  = new \DateTime($data['createdAt']);

        $this->assignStatusable($data);
    }
}