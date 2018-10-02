<?php

namespace tmcsolution\messagingserviceclient;

use yii\helpers\ArrayHelper;

/**
 * Сообщение.
 *
 * @package tmcsolution\messagingserviceclient
 */
class Message extends Statusable
{
    /**
     * @var int Идентификатор сообщения.
     */
    public $id;

    /**
     * @var int Идентификатор потребителя.
     */
    public $consumerId;

    /**
     * @var \DateTime Дата и время создания сообщения.
     */
    public $createdAt;

    /**
     * @var Email|null Сообщение электронной почты.
     */
    public $email;

    /**
     * @var Sms|null СМС-сообщение.
     */
    public $sms;

    /**
     * @var Telegram|null Сообщение Telegram.
     */
    public $telegram;

    /**
     * @var Viber|null Сообщение Viber.
     */
    public $viber;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIO_REQUEST  => ['email', 'sms', 'telegram', 'viber'],
            self::SCENARIO_RESPONSE => ['id', 'consumerId', 'createdAt', 'email', 'sms', 'telegram', 'viber'],
        ];
        return ArrayHelper::merge(parent::scenarios(), $scenarios);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['id', 'consumerId', 'createdAt'], 'required'],
            [['id', 'consumerId'], 'integer'],
            [
                'createdAt',
                'match',
                'pattern' => '/^[0-9]{4}-[01][0-9]-[0-3][0-9]T[0-2][0-9]:[0-5][0-9]:[0-5][0-9]\.[0-9]+Z*$/'
            ],
            ['createdAt', 'filter', 'filter' => function ($value) {
                return new \DateTime($value);
            }],
            [
                'email',
                'validateArray',
                'params' => [
                    'skipScenario' => [self::SCENARIO_REQUEST, self::SCENARIO_RESPONSE],
                    'filter'       => function ($attribute, $value) {
                        $email = new Email(['scenario' => $this->scenario]);
                        $email->load($value, '');
                        if (!$email->validate()) {
                            $this->addError($attribute, $email->errors);
                        }
                        return $email;
                    }
                ],
            ],
            [
                'sms',
                'validateArray',
                'params' => [
                    'skipScenario' => [self::SCENARIO_REQUEST, self::SCENARIO_RESPONSE],
                    'filter'       => function ($attribute, $value) {
                        $sms = new Sms(['scenario' => $this->scenario]);
                        $sms->load($value, '');
                        if (!$sms->validate()) {
                            $this->addError($attribute, $sms->errors);
                        }
                        return $sms;
                    }
                ],
            ],
            [
                'telegram',
                'validateArray',
                'params' => [
                    'skipScenario' => [self::SCENARIO_REQUEST, self::SCENARIO_RESPONSE],
                    'filter'       => function ($attribute, $value) {
                        $telegram = new Telegram(['scenario' => $this->scenario]);
                        $telegram->load($value, '');
                        if (!$telegram->validate()) {
                            $this->addError($attribute, $telegram->errors);
                        }
                        return $telegram;
                    }
                ],
            ],
            [
                'viber',
                'validateArray',
                'params' => [
                    'skipScenario' => [self::SCENARIO_REQUEST, self::SCENARIO_RESPONSE],
                    'filter'       => function ($attribute, $value) {
                        $viber = new Viber(['scenario' => $this->scenario]);
                        $viber->load($value, '');
                        if (!$viber->validate()) {
                            $this->addError($attribute, $viber->errors);
                        }
                        return $viber;
                    }
                ],
            ],
        ];
        return ArrayHelper::merge(parent::rules(), $rules);
    }
}