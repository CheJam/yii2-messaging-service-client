<?php

namespace tmcsolution\messagingserviceclient;

use yii\helpers\ArrayHelper;

/**
 * Базовый класс для сущности, имеющей статус и приоритет.
 *
 * @package tmcsolution\messagingserviceclient
 */
class Queueable extends Statusable
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
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIO_REQUEST  => ['attempts', 'priority'],
            self::SCENARIO_RESPONSE => ['attempts', 'priority'],
        ];
        return ArrayHelper::merge(parent::scenarios(), $scenarios);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            ['priority', 'required'],
            [['attempts', 'priority'], 'integer'],
        ];
        return ArrayHelper::merge(parent::rules(), $rules);
    }
}