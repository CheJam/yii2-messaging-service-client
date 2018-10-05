<?php

namespace tmcsolution\messagingserviceclient\base;

use tmcsolution\messagingserviceclient\Status;
use tmcsolution\messagingserviceclient\StatusRecord;
use yii\helpers\ArrayHelper;

/**
 * Базовый класс для сущности, имеющей статус.
 *
 * @package tmcsolution\messagingserviceclient
 */
class Statusable extends BaseModel
{
    /**
     * @var Status Статус сообщения.
     */
    public $status;

    /**
     * @var StatusRecord[] История смены статусов сообщения.
     */
    public $statusRecords;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIO_REQUEST  => [],
            self::SCENARIO_RESPONSE => ['status', 'statusRecords'],
        ];
        return ArrayHelper::merge(parent::scenarios(), $scenarios);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [
                'status',
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
            [
                'statusRecords',
                'validateArray',
                'params' => [
                    'filter' => function ($attribute, $value) {
                        $result = [];
                        foreach ($value as $i => $statusRecord) {
                            $selectedGateway = new StatusRecord(['scenario' => $this->scenario]);
                            $selectedGateway->load($statusRecord, '');
                            if (!$selectedGateway->validate()) {
                                $this->addModelErrors($attribute, $selectedGateway, $i);
                            }
                            $result[] = $selectedGateway;
                        }
                        return $result;
                    }
                ],
            ],
        ];
        return ArrayHelper::merge(parent::rules(), $rules);
    }
}