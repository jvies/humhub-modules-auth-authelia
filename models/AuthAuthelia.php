<?php
namespace humhub\modules\authAuthelia\models;

use humhub\modules\user\models\Auth;

/**
 * @inerhitdoc
 *
 * @property string $authelia_sid Authelia shared session identifier
 */
class AuthAuthelia extends Auth
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['authelia_sid'], 'string', 'max' => 36]
        ]);
    }
}
