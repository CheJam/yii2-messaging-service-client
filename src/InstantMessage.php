<?php

namespace tmcsolution\messagingserviceclient;

use yii\helpers\ArrayHelper;

/**
 * Базовый класс для IM-сообщений.
 *
 * @package tmcsolution\messagingserviceclient
 */
class InstantMessage extends DriverMessage
{
    /**
     * @var int Идентификатор сообщения.
     */
    public $id;

    /**
     * @var string Идентификатор получателя.
     */
    public $to;

    /**
     * @var string Текстовая часть сообщения.
     */
    public $text;

    /**
     * @var int|null Серверный идентификатор отправленного сообщения.
     */
    public $sentId;

    /**
     * @var int|string|null Идентификатор IM-пользователя.
     */
    public $userId;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIO_REQUEST  => ['to', 'text'],
            self::SCENARIO_RESPONSE => ['id', 'to', 'text', 'sentId', 'userId'],
        ];
        return ArrayHelper::merge(parent::scenarios(), $scenarios);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['id', 'to', 'text'], 'required'],
            [['id', 'sentId'], 'integer'],
            [['to', 'text'], 'string'],
            ['userId', 'validateIntOrStr'],
        ];
        return ArrayHelper::merge(parent::rules(), $rules);
    }

    /**
     * Проверяет, является ли значение атрибута целым числом или строкой.
     *
     * @param string $attribute Атрибут, проверяемый в настоящее время.
     * @param array  $params    Дополнительные пары имя-значение, заданные в правиле.
     */
    public function validateIntOrStr($attribute, $params)
    {
        if ($this->hasErrors() || is_null($this->$attribute)) {
            return;
        }

        if (!is_int($this->$attribute) && !is_string($this->$attribute)) {
            $this->addError($attribute, 'Значение не является целым числом или строкой.');
        }
    }
}