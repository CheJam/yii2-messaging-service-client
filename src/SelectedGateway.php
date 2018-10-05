<?php

namespace tmcsolution\messagingserviceclient;

use tmcsolution\messagingserviceclient\base\BaseModel;
use yii\helpers\ArrayHelper;

/**
 * Выбранный для отправки шлюз.
 *
 * @package tmcsolution\messagingserviceclient
 */
class SelectedGateway extends BaseModel
{
    /**
     * @var string Название шлюза.
     */
    public $name;

    /**
     * @var int Приоритет шлюза.
     */
    public $priority;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIO_REQUEST  => ['name', 'priority'],
            self::SCENARIO_RESPONSE => ['name', 'priority'],
        ];
        return ArrayHelper::merge(parent::scenarios(), $scenarios);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['name', 'priority'], 'required'],
            ['name', 'string'],
            ['priority', 'integer'],
        ];
        return ArrayHelper::merge(parent::rules(), $rules);
    }
}