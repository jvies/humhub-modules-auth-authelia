<?php
namespace humhub\modules\authAuthelia;

use humhub\modules\authAuthelia\authclient\Authelia;
use humhub\modules\authAuthelia\models\ConfigureForm;
use humhub\modules\user\authclient\Collection;
use yii\base\Event;

class Events
{
    /**
     * @param Event $event
     * @return void
     */
    public static function onAuthClientCollectionInit($event)
    {
        /** @var Collection $authClientCollection */
        $authClientCollection = $event->sender;

        $config = new ConfigureForm();
        if ($config->enabled) {
            $authClientCollection->setClient(Authelia::DEFAULT_NAME, [
                'class' => Authelia::class,
                'clientId' => $config->clientId,
                'clientSecret' => $config->clientSecret,
            ]);
        }
    }

}
