<?php

namespace tmcsolution\messagingserviceclient;

use yii\helpers\ArrayHelper;

/**
 * Viber-сообщение.
 *
 * @package tmcsolution\messagingserviceclient
 */
class Viber extends InstantMessage
{
    /**
     * @var string|null Имя отправителя.
     */
    public $from;

    /**
     * @var string|null Ссылка на аватар отправителя.
     */
    public $avatar;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIO_REQUEST  => ['from', 'avatar'],
            self::SCENARIO_RESPONSE => ['from', 'avatar'],
        ];
        return ArrayHelper::merge(parent::scenarios(), $scenarios);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['from', 'avatar'], 'string'],
        ];
        return ArrayHelper::merge(parent::rules(), $rules);
    }
}