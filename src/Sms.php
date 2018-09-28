<?php

namespace tmcsolution\messagingserviceclient;

/**
 * SMS-сообщение.
 *
 * @package tmcsolution\messagingserviceclient
 */
class Sms
{
    use Prioritizable, Statusable;

    public $from;
    public $to;
    public $text;

    private $_id;

    /**
     * Идентификатор SMS-сообщения.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Создаёт SMS-сообщение из массива данных.
     *
     * @param $data array Массив данных, полученных в результате парсинга JSON-ответа сервиса.
     */
    public function __construct($data)
    {
        $this->from = $data['from'];
        $this->to   = $data['to'];
        $this->text = $data['text'];

        $this->_id = $data['id'];

        $this->assignPrioritizable($data);
        $this->assignStatusable($data);
    }
}