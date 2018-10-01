<?php

namespace tmcsolution\messagingserviceclient;

use yii\base\Model;

/**
 * Базовый класс для сущности.
 *
 * @package tmcsolution\messagingserviceclient
 */
class BaseModel extends Model
{
    /**
     * Для создания запроса к сервису.
     */
    const SCENARIO_REQUEST = 'request';

    /**
     * Для обработки ответа сервиса.
     */
    const SCENARIO_RESPONSE = 'response';

    /**
     * Проверяет, является ли значение атрибута массивом.
     * Если задан фильтр для атрибута, применяет его.
     *
     * @param string $attribute Атрибут, проверяемый в настоящее время.
     * @param array  $params    Дополнительные пары имя-значение, заданные в правиле:
     *                          * filter - функция для преобразования значения,
     *                          * skipScenario - строка с названием сценария или массив строк с названиями сценариев,
     *                          при которых значение атрибута может быть не массивом.
     */
    public function validateArray($attribute, $params)
    {
        if ($this->hasErrors()) {
            return;
        }

        if (!is_array($this->$attribute) &&
            (!isset($params['skipScenario']) ||
             ((!is_array($params['skipScenario']) || !in_array($this->scenario, $params['skipScenario'])) &&
              ($this->scenario !== $params['skipScenario'])))) {
            $this->addError($attribute, 'Значение не является массивом.');
            return;
        }

        if (isset($params['filter']) && is_callable($params['filter'])) {
            $this->$attribute = $params['filter']($attribute, $this->$attribute);
        }
    }
}