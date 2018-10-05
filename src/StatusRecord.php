<?php

namespace tmcsolution\messagingserviceclient;

use tmcsolution\messagingserviceclient\base\BaseModel;
use yii\helpers\ArrayHelper;

/**
 * Запись истории изменений статусов.
 *
 * @package tmcsolution\messagingserviceclient
 */
class StatusRecord extends BaseModel
{
    /**
     * @var Status Предыдущий статус.
     */
    public $previous;

    /**
     * @var Status Новый статус.
     */
    public $new;

    /**
     * @var \DateTime Дата и время смены статуса.
     */
    public $createdAt;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIO_REQUEST  => [],
            self::SCENARIO_RESPONSE => ['previous', 'new', 'createdAt'],
        ];
        return ArrayHelper::merge(parent::scenarios(), $scenarios);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            ['createdAt', 'required'],
            [
                'createdAt',
                'match',
                'pattern' => '/^[0-9]{4}-[01][0-9]-[0-3][0-9]T[0-2][0-9]:[0-5][0-9]:[0-5][0-9]\.[0-9]+Z*$/'
            ],
            ['createdAt', 'filter', 'filter' => function ($value) {
                return new \DateTime($value);
            }],
            [
                ['previous', 'new'],
                'validateArray',
                'params' => [
                    'filter' => function ($attribute, $value) {
                        $status = new Status(['scenario' => $this->scenario]);
                        $status->load($value, '');
                        if (!$status->validate()) {
                            $this->addModelErrors($attribute, $status);
                        }
                        return $status;
                    }
                ],
            ],
        ];
        return ArrayHelper::merge(parent::rules(), $rules);
    }
}