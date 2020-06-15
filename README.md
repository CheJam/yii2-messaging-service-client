# yii2-messaging-service-client

------

Yii2 расширение-клиент для взаимодействия с [MessagingService](https://bitbucket.org/tmcsolution/messagingservice). Представляет из себя Yii2 компоненту с моделями.



### Модели

```
Message - Сообщение для отправки.
	Email 	 - Сообщение для отправки через электронную почту.
	Sms 	 - Сообщение для отправки через SMS-шлюз.
	Telegram - Сообщение для отправки через Telegram.
	Viber 	 - Сообщение для отправки через Viber.
```

Перечислены только те модели, которые пользователь может создавать самостоятельно. Остальные являются либо их абстракциями, либо создаются только самой компонентой.



### Конфигурация

Пример конфигурации:

```php
'components' => [
	...
    'messagingService' => [
        'class' => 'tmcsolution\messagingserviceclient\MessagingServiceClient',
        'authToken' => 'eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9...',
        'baseUrl' => 'https://localhost:5001/v1',
        'requestConfig' => [
            'format' => Client::FORMAT_JSON
        ],
        'responseConfig' => [
            'format' => Client::FORMAT_JSON
        ],
    ],
    ...
],
```

Подробнее см. [yii2-gustaf-client](https://bitbucket.org/tmcsolution/yii2-gustaf-client/src/master/README.md).



### Использование

Компонента может использоваться для создания сообщений и для получения информации о них. Для этого существуют соответствующие методы `createMessages` и `getMessages`, оперирующие с моделью `Message`. Свойства этой модели должны устанавливаться с помощью метода `load()`, а затем проверяться на правильность с помощью метода `validate()`. 

Валидация модели происходит рекурсивно, то есть сначала проверяется сама модель, а затем модели, установленные в качестве значений свойств. В связи с этим, если при проверке обнаружатся ошибки в дочерних моделях, они будут отформатированы следующим образом (пример массива `$model->errors` для модели `Message`):

```json
{
  "id": [
    "Значение «Id» должно быть целым числом."
  ],
  "email": [
    "status.code: Значение «Code» должно быть целым числом.",
    "status.message: Значение «Message» должно быть строкой.",
    "statusRecords[0].previous.code: Значение «Code» должно быть целым числом.",
    "statusRecords[0].new.code: Значение «Code» должно быть целым числом.",
    "statusRecords[0].new.message: Значение «Message» должно быть строкой.",
    "statusRecords[1].createdAt: Необходимо заполнить «Created At».",
    "statusRecords[1].previous.message: Значение «Message» должно быть строкой.",
    "id: Значение «Id» должно быть целым числом."
  ]
}
```

Здесь `status` является дочерней моделью, а `statusRecords` массивом дочерних моделей сущности `Email`.

При создании модели необходимо указать сценарий:

```php
// При использовании в запросе к сервису.
$message = new Message(['scenario' => Message::SCENARIO_REQUEST]);

// При обработке ответа сервиса.
// Обычно не требуется, т. к. вышеперечисленные методы возвращают уже созданные модели.
$message = new Message(['scenario' => Message::SCENARIO_RESPONSE]); 
```

Для подключения расширения необходимо прописать в `composer.json` следующие строки:

```json
"require": {
    ...
    "tmcsolution/yii2-messaging-service-client": "^1.0.0",
    ...
},
...
"repositories": [
    ...
    {
        "type": "vcs",
        "url": "git@bitbucket.org:tmcsolution/yii2-messaging-service-client.git"
    },
    ...
]
```



Пример создания сообщения:

```php
$data = [
    'email'    => [
        'from'     => ['Messaging Service', 'noreply@ms.chejam.com'],
        'to'       => ['Vasya Pupkin', 'vasya@mail.net'],
        'title'    => 'Hello!',
        'body'     => 'Hello from Yii2!',
        'format'   => 'plain',
        'attempts' => 2,
        'priority' => 1,
    ],
    'telegram' => [
        'to'       => 'vasya_pupkin',
        'text'     => 'Hello from Yii2!',
        'gateways' => [
            [
                'name'     => 'TelegramAccount',
                'priority' => 1,
            ]
        ],
        'priority' => 2,
    ],
    'viber'    => [
        'from'     => 'Messaging Service',
        'avatar'   => 'https://upyachka.io/img/up4kman.gif',
        'to'       => 'Vasya\'s_Pupkin_Unique_Identifier',
        'text'     => 'Hello from Yii2!',
        'gateways' => [
            [
                'name'     => 'ViberAccount1',
                'priority' => null,
            ],
            [
                'name'     => 'ViberAccount2',
                'priority' => 2,
            ]
        ],
        'priority' => 2,
    ],
];

$message = new Message(['scenario' => Message::SCENARIO_REQUEST]);
$message->load($data, '');
if (!$message->validate()) {
    return $message->errors;
}

$messages = Yii::$app->messagingService->createMessages([$message]);

// $messages содержит массив созданных моделей Message
```



Пример получения сообщений:

```php
// Всех сообщений
$messages = Yii::$app->messagingService->getMessages();

// Только с id=5 и id=17
$messages = Yii::$app->messagingService->getMessages([5, 17]);

// $messages содержит массив созданных моделей Message
```

