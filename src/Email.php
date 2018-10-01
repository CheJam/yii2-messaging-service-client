<?php

namespace tmcsolution\messagingserviceclient;

use yii\helpers\ArrayHelper;

/**
 * Сообщение электронной почты.
 *
 * @package tmcsolution\messagingserviceclient
 */
class Email extends DriverMessage
{
    /**
     * @var int Идентификатор EMail-сообщения.
     */
    public $id;

    /**
     * @var string[] Адрес отправителя в виде массива ["Василий", "vasya@ya.ru"] или ["vasya@ya.ru"].
     */
    public $from;

    /**
     * @var string[] Адрес получателя в виде массива ["Василий", "vasya@ya.ru"] или ["vasya@ya.ru"].
     */
    public $to;

    /**
     * @var string Тема сообщения.
     */
    public $title;

    /**
     * @var string Текстовая часть сообщения.
     */
    public $body;

    /**
     * @var string Формат текстовой части: "plain" или "html".
     */
    public $format;

    /**
     * @var string|null Название почтового сервера, через который отправлено сообщение.
     */
    public $sentVia;

    /**
     * @var string Токен сообщения для отслеживания прочтения.
     */
    public $token;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIO_REQUEST => ['from', 'to', 'title', 'body', 'format'],
            self::SCENARIO_RESPONSE => ['id', 'from', 'to', 'title', 'body', 'format', 'sentVia', 'token'],
        ];
        return ArrayHelper::merge(parent::scenarios(), $scenarios);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['id', 'from', 'to', 'title', 'body', 'format', 'token'], 'required'],
            ['id', 'integer'],
            [['title', 'body', 'format', 'sentVia', 'token'], 'string'],
            [['from', 'to'], 'validateArray'],
        ];
        return ArrayHelper::merge(parent::rules(), $rules);
    }
}