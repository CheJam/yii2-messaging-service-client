<?php

namespace tmcsolution\messagingserviceclient;

/**
 * Дополнение для сущности, имеющей статус.
 *
 * @package tmcsolution\messagingserviceclient
 */
trait Statusable
{
    use Base;

    private $_status;
    private $_statusRecords;

    /**
     * Статус сообщения.
     *
     * @return Status
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * История смены статусов сообщения.
     *
     * @return StatusRecord[]
     */
    public function getStatusRecords()
    {
        return $this->_statusRecords;
    }

    /**
     * Присваивает полям в данном дополнении значения из массива данных.
     *
     * @param $data array Массив данных, полученных в результате парсинга JSON-ответа сервиса.
     */
    protected function assignStatusable($data)
    {
        if (isset($data['status'])) {
            $this->_status = new Status($data['status']);
        }

        if (isset($data['statusRecords'])) {
            foreach ($data['statusRecords'] as $statusRecord) {
                $this->_statusRecords[] = new StatusRecord($statusRecord);
            }
        }
    }
}