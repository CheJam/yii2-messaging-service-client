<?php

namespace tmcsolution\messagingserviceclient;

use tmcsolution\messagingserviceclient\base\Queueable;
use yii\helpers\ArrayHelper;

/**
 * SMS-сообщение.
 *
 * @package tmcsolution\messagingserviceclient
 */
class Sms extends Queueable
{
    /**
     * @var int Идентификатор SMS-сообщения.
     */
    public $id;

    /**
     * @var string Номер или текстовый идентификатор отправителя.
     */
    public $from;

    /**
     * @var string Номер получателя.
     */
    public $to;

    /**
     * @var string Текстовая часть сообщения.
     */
    public $text;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIO_REQUEST  => ['from', 'to', 'text'],
            self::SCENARIO_RESPONSE => ['id', 'from', 'to', 'text'],
        ];
        return ArrayHelper::merge(parent::scenarios(), $scenarios);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['id', 'from', 'to', 'text'], 'required'],
            ['id', 'integer'],
            [['from', 'to', 'text'], 'string'],
        ];
        return ArrayHelper::merge(parent::rules(), $rules);
    }
}