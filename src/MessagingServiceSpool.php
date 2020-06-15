<?php

namespace tmcsolution\messagingserviceclient;

use Swift_Mime_SimpleMessage;
use Swift_Spool;
use Swift_Transport;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\web\HttpException;

/**
 * Class MessagingServiceSpool
 *
 * @property-write int   $attempts Число попыток отправки каждого сообщения
 * @property-write array $gateways Email-шлюзы для отправки по приоритетам
 *
 * @package tmcsolution\messagingserviceclient
 */
class MessagingServiceSpool extends BaseObject implements Swift_Spool
{
    /**
     * @var int
     */
    private $_attempts = 1;

    /**
     * @var array
     */
    private $_gateways = [];

    /**
     * {@inheritdoc}
     */
    public function start()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * Задаёт число попыток отправки каждого сообщения
     *
     * @param int $value
     */
    public function setAttempts($value)
    {
        $this->_attempts = $value;
    }

    /**
     * Задаёт email-шлюзы для отправки по приоритетам
     *
     * @param array $value
     */
    public function setGateways($value)
    {
        $this->_gateways = $value;
    }

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function queueMessage(Swift_Mime_SimpleMessage $message)
    {
        if (empty(Yii::$app->messagingService)) {
            throw new InvalidConfigException('Messaging service client not initialized');
        }

        $contentType = $message->getContentType();
        $contentTypeArr = explode('/', $contentType);
        $contentType = end($contentTypeArr);
        if (!in_array($contentType, ['html', 'plain'])) {
            $contentType = 'plain';
        }

        $base = [
            'from'     => self::transformAddress($message->getFrom()),
            'title'    => $message->getSubject(),
            'body'     => $message->getBody(),
            'format'   => $contentType,
            'attempts' => $this->_attempts,
            'gateways' => $this->_gateways ?: null,
            'priority' => 1,
        ];

        $messages = [];
        foreach ($message->getTo() as $email => $name) {
            $msg = new Message(['scenario' => Message::SCENARIO_REQUEST]);
            $data = $base;
            $data['to'] = self::transformAddress($email, $name);
            $msg->load(['email' => $data], '');

            if ($msg->validate()) {
                $messages[] = $msg;
            } else {
                $errors = [];
                foreach ($msg->errors as $attribute => $errorMessages) {
                    $errors[] = sprintf("-> %s:\n\t- %s", $attribute, implode("\n\t- ", $errorMessages));
                }

                throw new InvalidConfigException("Invalid email data. Inner errors:\n" . implode(PHP_EOL, $errors));
            }
        }

        try {
            $result = Yii::$app->messagingService->createMessages($messages);

            return count($result) === count($messages);
        } catch (HttpException $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function flushQueue(Swift_Transport $transport, &$failedRecipients = null)
    {
        return 0;
    }

    /**
     * Преобразовывает email-адрес из формата Mailer'а в формат MessagingService
     *
     * @param mixed       $address
     * @param string|null $name
     *
     * @return string[]
     */
    private static function transformAddress($address, $name = null)
    {
        if (is_array($address)) {
            $name = reset($address);
            $result = [key($address)];
        } elseif (is_string($address)) {
            $result = [$address];
        } else {
            throw new InvalidArgumentException('Unknown email address format');
        }

        if (isset($name)) {
            array_unshift($result, $name);
        }

        return $result;
    }
}