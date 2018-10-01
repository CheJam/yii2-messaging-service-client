<?php

namespace tmcsolution\messagingserviceclient;

use yii\helpers\ArrayHelper;

/**
 * Базовый класс для сущности, имеющей шлюзы, статус и приоритет.
 *
 * @package tmcsolution\messagingserviceclient
 */
class DriverMessage extends Queueable
{
    /**
     * @var SelectedGateway[] Список шлюзов.
     */
    public $gateways;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIO_REQUEST  => ['gateways'],
            self::SCENARIO_RESPONSE => ['gateways'],
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
                'gateways',
                'validateArray',
                'params' => [
                    'skipScenario' => self::SCENARIO_REQUEST,
                    'filter'       => function ($attribute, $value) {
                        $result = [];
                        foreach ($value as $gateway) {
                            $selectedGateway = new SelectedGateway(['scenario' => $this->scenario]);
                            $selectedGateway->load($gateway, '');
                            if (!$selectedGateway->validate()) {
                                $this->addError($attribute, $selectedGateway->errors);
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