<?php

namespace tmcsolution\messagingserviceclient;

/**
 * Запись истории изменений статусов.
 *
 * @package tmcsolution\messagingserviceclient
 */
class StatusRecord
{
    use Base;

    private $_previous;
    private $_new;
    private $_createdAt;

    /**
     * Предыдущий статус.
     *
     * @return Status
     */
    public function getPrevious()
    {
        return $this->_previous;
    }

    /**
     * Новый статус.
     *
     * @return Status
     */
    public function getNew()
    {
        return $this->_new;
    }

    /**
     * Дата и время смены статуса.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->_createdAt;
    }

    /**
     * Создаёт запись истории изменений статусов из массива данных.
     *
     * @param $data array Массив данных, полученных в результате парсинга JSON-ответа сервиса.
     */
    public function __construct($data)
    {
        $this->_previous  = new Status($data['previous']);
        $this->_new       = new Status($data['new']);
        $this->_createdAt = new \DateTime($data['createdAt']);
    }
}