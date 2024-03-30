<?php
namespace humhub\modules\authAuthelia\models;

use humhub\modules\authAuthelia\authclient\Authelia;
use humhub\modules\authAuthelia\Module;
use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * The module configuration model
 */
class ConfigureForm extends Model
{
    public const DEFAULT_TITLE = 'Connect with Authelia';

    /**
     * @var boolean
     */
    public $enabled = false;
    /**
     * @var string
     */
    public $clientId;
    /**
     * @var string
     */
    public $clientSecret;
    /**
     * @var string
     */
    public $baseUrl;
    /**
     * @var string readonly
     */
    public $redirectUri;
    /**
     * @var string
     */
    public $usernameMapper = 'sub';
    /**
     * @var string
     */
    public $title;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clientId', 'clientSecret', 'baseUrl', 'usernameMapper'], 'required'],
            [['clientId', 'clientSecret', 'baseUrl', 'usernameMapper', 'title'], 'string'],
            [['enabled'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        /** @var Module $module */
        $module = Yii::$app->getModule('auth-authelia');
        $settings = $module->settings;

        $this->enabled = (bool)$settings->get('enabled', $this->enabled);
        $this->clientId = $settings->get('clientId');
        $this->clientSecret = $settings->get('clientSecret');
        $this->baseUrl = $settings->get('baseUrl');
        $this->usernameMapper = $settings->get('usernameMapper', $this->usernameMapper);
        $this->title = $settings->get('title', Yii::t('AuthAutheliaModule.base', self::DEFAULT_TITLE));
        $this->redirectUri = Url::to(['/user/auth/external', 'authclient' => Authelia::DEFAULT_NAME], true);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'enabled' => Yii::t('AuthAutheliaModule.base', 'Enable this auth client'),
            'clientId' => Yii::t('AuthAutheliaModule.base', 'Client ID'),
            'clientSecret' => Yii::t('AuthAutheliaModule.base', 'Client secret key'),
            'baseUrl' => Yii::t('AuthAutheliaModule.base', 'Base URL'),
            'usernameMapper' => Yii::t('AuthAutheliaModule.base', 'Authelia attribute to use to get username on account creation'),
            'title' => Yii::t('AuthAutheliaModule.base', 'Title of the button'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'clientId' => Yii::t('AuthAutheliaModule.base', 'The client id provided by Authelia'),
            'clientSecret' => Yii::t('AuthAutheliaModule.base', 'Client secret is shared with Authelia'),
            'baseUrl' => 'Depending on your configuration: https://idp-domain.tdl or https://idp-domain.tdl/auth',
            'usernameMapper' => Yii::t('AuthAutheliaModule.base', '`preferred_username` (to use Authelia username), `sub` (to use Authelia ID) or other custom Token Claim Name'),
            'title' => Yii::t('AuthAutheliaModule.base', 'If you set a custom title, it will not be translated to the user\'s language unless you have a custom translation file in the protected/config folder. Leave blank to set default title.'),
        ];
    }

    /**
     * Saves module settings
     */
    public function save()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('auth-authelia');

        $module->settings->set('enabled', $this->enabled);
        $module->settings->set('clientId', trim((string)$this->clientId));
        $module->settings->set('clientSecret', trim((string)$this->clientSecret));
        $module->settings->set('baseUrl', rtrim(trim((string)$this->baseUrl), '/'));
        $module->settings->set('usernameMapper', trim((string)$this->usernameMapper));
        if (!$this->title) {
            $this->title = self::DEFAULT_TITLE;
        }
        $module->settings->set('title', $this->title);

        return true;
    }
}
