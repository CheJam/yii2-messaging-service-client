<?php

namespace tmcsolution\messagingserviceclient;

/**
 * Дополнение для сущности, имеющей шлюзы.
 *
 * @package tmcsolution\messagingserviceclient
 */
trait Gatewayable
{
    /**
     * @var SelectedGateway[] Список шлюзов.
     */
    public $gateways;

    /**
     * Присваивает полям в данном дополнении значения из массива данных.
     *
     * @param $data array Массив данных, полученных в результате парсинга JSON-ответа сервиса.
     */
    protected function assignGatewayable($data)
    {
        if (isset($data['gateways'])) {
            foreach ($data['gateways'] as $gateway) {
                $this->gateways[] = new SelectedGateway($gateway);
            }
        }
    }
}