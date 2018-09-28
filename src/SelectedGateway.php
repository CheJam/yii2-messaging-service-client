<?php

namespace tmcsolution\messagingserviceclient;

use yii\base\BaseObject;

/**
 * Выбранный для отправки шлюз.
 *
 * @package tmcsolution\messagingserviceclient
 */
class SelectedGateway extends BaseObject
{
    /**
     * @var string Название шлюза.
     */
    public $name;

    /**
     * @var int Приоритет шлюза.
     */
    public $priority;
}