<?php
namespace humhub\modules\authAuthelia\authclient;

use humhub\modules\authAuthelia\models\AuthAuthelia;
use humhub\modules\authAuthelia\models\ConfigureForm;
use humhub\modules\authAuthelia\Module;
use humhub\modules\user\authclient\interfaces\PrimaryClient;
use humhub\modules\user\models\Auth;
use humhub\modules\user\models\User;
use humhub\modules\user\services\AuthClientUserService;
use PDOException;
use Yii;
use yii\authclient\InvalidResponseException;
use yii\authclient\OpenIdConnect;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\helpers\BaseInflector;

/**
 * With PrimaryClient, the user will have the `auth_mode` field in the `user` table set to 'Authelia'.
 * This will avoid showing the "Change Password" tab when logged in with Authelia
 */
class Authelia extends OpenIdConnect implements PrimaryClient
{
    public const DEFAULT_NAME = 'Authelia';

    /**
     * @inheritdoc
     */
    public $scope = 'openid profile email';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!class_exists('Jose\Component\KeyManagement\JWKFactory')) {
            require_once Yii::getAlias('@auth-authelia/vendor/autoload.php');
        }

        $config = new ConfigureForm();
        $this->issuerUrl = $config->baseUrl;
        $this->apiBaseUrl = $this->issuerUrl . '/api/oidc';

        parent::init();
    }

    /**
     * @param $request
     * @param $accessToken
     * @return void
     */
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        $data = $request->getData();
        $data['Authorization'] = 'Bearer ' . $accessToken->getToken();
        $request->setHeaders($data);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return self::DEFAULT_NAME;
    }

    /**
     * @inheridoc
     */
    public function getUser()
    {
        $userAttributes = $this->getUserAttributes();

        if (array_key_exists('id', $userAttributes)) {
            $userAuth = Auth::findOne(['source' => self::DEFAULT_NAME, 'source_id' => $userAttributes['id']]);
            if ($userAuth !== null && $userAuth->user !== null) {
                return $userAuth->user;
            }
        }

        if (array_key_exists('email', $userAttributes)) {
            $userByEmail = User::findOne(['email' => $userAttributes['email']]);
            if ($userByEmail !== null) {
                return $userByEmail;
            }
        }

        if (array_key_exists('username', $userAttributes)) {
            $userByUsername = User::findOne(['username' => $userAttributes['username']]);
            if ($userByUsername !== null) {
                return $userByUsername;
            }
        }

        return null;
    }

    /**
     * @inheridoc
     */
    protected function initUserAttributes()
    {
        try {
            return $this->api('userinfo');
        } catch (InvalidResponseException|\Exception $e) {
            return [];
        }
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return self::DEFAULT_NAME;
    }

    /**
     * @inheridoc
     */
    protected function defaultTitle()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('auth-authelia');
        return $module->settings->get('title', Yii::t('AuthAutheliaModule.base', ConfigureForm::DEFAULT_TITLE));
    }

    protected function defaultViewOptions()
    {
        return [
            'cssIcon' => 'fa fa-sign-in',
            'buttonBackgroundColor' => '#e0492f',
        ];
    }

    protected function defaultNormalizeUserAttributeMap()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('auth-authelia');

        return [
            'id' => 'sub',
            'username' => $module->settings->get('usernameMapper'),
            'firstname' => 'given_name',
            'lastname' => 'family_name',
            'email' => 'email',
        ];
    }

    /**
     * If the username sent by Authelia is the user's email, it is replaced by a username auto-generated from the first and last name (CamelCase formatted)
     * @inerhitdoc
     * @throws InvalidConfigException
     */
    protected function normalizeUserAttributes($attributes)
    {
        $attributes = parent::normalizeUserAttributes($attributes);
        if (
            isset($attributes['username'], $attributes['email'])
            && $attributes['username'] === $attributes['email']
        ) {
            $attributes['username'] = BaseInflector::id2camel(
                BaseInflector::slug(
                    $attributes['firstname'] . ' ' . $attributes['lastname']
                )
            );
        }
        return $attributes;
    }
}
