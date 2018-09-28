<?php

namespace tmcsolution\messagingserviceclient;

/**
 * Дополнение для сущности, имеющей приоритет.
 *
 * @package tmcsolution\messagingserviceclient
 */
trait Prioritizable
{
    /**
     * @var int|null Число попыток отправки.
     */
    public $attempts;

    /**
     * @var int Приоритет.
     */
    public $priority;

    /**
     * Присваивает полям в данном дополнении значения из массива данных.
     *
     * @param $data array Массив данных, полученных в результате парсинга JSON-ответа сервиса.
     */
    protected function assignPrioritizable($data)
    {
        $this->attempts = $data['attempts'];
        $this->priority = $data['priority'];
    }
}