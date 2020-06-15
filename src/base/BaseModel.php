<?php

namespace tmcsolution\messagingserviceclient\base;

use yii\base\Model;
use yii\validators\InlineValidator;

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
     * @inheritdoc
     */
    public function fields()
    {
        return $this->scenarios()[$this->scenario];
    }

    /**
     * Добавляет ошибки дочерней модели.
     *
     * @param string   $attribute Название атрибута, представляющего модель.
     * @param Model    $model     Дочерняя модель с ошибками.
     * @param int|null $key       Если дочерняя модель - элемент массива, ключ элемента.
     */
    public function addModelErrors($attribute, $model, $key = null)
    {
        if (!is_null($key)) {
            $attribute = sprintf('%s[%d]', $attribute, $key);
        }

        foreach ($model->errors as $attr => $errors) {
            $validateArray = false;

            $attrName = $attr;
            if (preg_match('/(.+)\[.+]/', $attr, $matches) === 1) {
                $attrName = $matches[1];
            }

            foreach ($model->getActiveValidators($attrName) as $validator) {
                if ($validator instanceof InlineValidator && $validator->method === 'validateArray') {
                    $validateArray = true;
                    break;
                }
            }

            foreach ($errors as $error) {
                $this->addError($attribute, $validateArray ? ($attr . '.' . $error) : sprintf('%s: %s', $attr, $error));
            }
        }
    }

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

        // Проверяет, является ли значение массивом.
        // Если нет, то проверяет, установлен ли параметр skipScenario.
        // Если в skipScenario установлен массив, то проверяет, есть ли в нём строка с названием данного атрибута.
        // Если в skipScenario установлена строка, то проверяет, равна ли она названию текущего сценария.
        if (!(is_array($this->$attribute) ||
              (isset($params['skipScenario']) &&
               ((is_array($params['skipScenario']) && in_array($this->scenario, $params['skipScenario'])) ||
                ($this->scenario === $params['skipScenario']))))) {
            $this->addError($attribute, 'Значение не является массивом.');
            return;
        }

        if (isset($params['filter']) && is_callable($params['filter'])) {
            $this->$attribute = $params['filter']($attribute, $this->$attribute);
        }
    }
}