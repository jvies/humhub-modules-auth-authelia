<?php
namespace humhub\modules\authAuthelia;

use humhub\components\Module as BaseModule;
use yii\helpers\Url;

class Module extends BaseModule
{
    /**
     * @var string defines the icon
     */
    public $icon = 'sign-in';

    /**
     * @var string defines path for resources, including the screenshots path for the marketplace
     */
    public $resourcesPath = 'resources';

    /**
     * @inheritdoc
     */
    public function getConfigUrl()
    {
        return Url::to(['/auth-authelia/config']);
    }

    public function getName()
    {
        return 'Authelia Sign-In';
    }

    public function getDescription()
    {
        return 'Integrating Authelia Sign-In (OAuth 2.0)';
    }
}
