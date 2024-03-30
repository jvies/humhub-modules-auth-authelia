<?php

use humhub\modules\authAuthelia\Events;
use humhub\modules\user\authclient\Collection;

/** @noinspection MissedFieldInspection */
return [
    'id' => 'auth-authelia',
    'class' => humhub\modules\authAuthelia\Module::class,
    'namespace' => 'humhub\modules\authAuthelia',
    'events' => [
        [
            'class' => Collection::class,
            'event' => Collection::EVENT_AFTER_CLIENTS_SET,
            'callback' => [Events::class, 'onAuthClientCollectionInit']
        ],
    ]
];
