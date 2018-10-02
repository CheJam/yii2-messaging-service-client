<?php

namespace tmcsolution\messagingserviceclient;

use yii\helpers\ArrayHelper;

/**
 * Статус сущности.
 *
 * @package tmcsolution\messagingserviceclient
 */
class Status extends BaseModel
{
    /**
     * @var int Код статуса.
     */
    public $code;

    /**
     * @var string Описание статуса.
     */
    public $message;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIO_REQUEST  => [],
            self::SCENARIO_RESPONSE => ['code', 'message'],
        ];
        return ArrayHelper::merge(parent::scenarios(), $scenarios);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['code', 'message'], 'required'],
            ['code', 'integer'],
            ['message', 'string'],
        ];
        return ArrayHelper::merge(parent::rules(), $rules);
    }
}