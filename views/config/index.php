<?php

/* @var $this View */

/* @var $model ConfigureForm */

use humhub\libs\Html;
use humhub\modules\authAuthelia\models\ConfigureForm;
use humhub\modules\authAuthelia\Module;
use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\modules\ui\view\components\View;
use humhub\widgets\Button;
use yii\bootstrap\Alert;

/** @var Module $module */
$module = Yii::$app->getModule('auth-authelia');

$requirements = include $module->basePath . '/' . 'requirements.php';
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('AuthAutheliaModule.base', '<strong>Authelia</strong> Sign-In configuration') ?>
        <div class="help-block"><?= $module->getDescription() ?></div>
    </div>

    <div class="panel-body">
        <?php if ($requirements): ?>
            <?= Alert::widget([
                'options' => ['class' => 'alert-danger'],
                'body' => Html::tag('strong', 'A requirement is not met: ' . $requirements),
            ]) ?>
        <?php endif; ?>

        <div>
            <div><?= Yii::t('AuthAutheliaModule.base', 'On Authelia, create a client for Humhub and configure it:') ?></div>
            <pre>identity_providers:
  oidc:
    clients:
      - client_id: '<?= $model->clientId ?>'
        client_secret: '<?= $model->clientSecret ?>'
        redirect_uris:
          - '<?= $model->redirectUri ?>'
        authorization_policy: 'one_factor'
        </div>
        <br>

        <?php $form = ActiveForm::begin(['acknowledge' => true]) ?>

        <?= $form->field($model, 'enabled')->checkbox() ?>
        <?= $form->field($model, 'baseUrl') ?>
        <?= $form->field($model, 'clientId') ?>
        <?= $form->field($model, 'clientSecret')->textInput(['type' => 'password']) ?>
        <?= $form->field($model, 'redirectUri')->textInput(['readonly' => true]) ?>
        <?= $form->field($model, 'usernameMapper') ?>

        <?= $form->beginCollapsibleFields(Yii::t('AuthAutheliaModule.base', 'Advanced settings (optional)')) ?>
        <?= $form->field($model, 'title') ?>
        <?= $form->endCollapsibleFields(); ?>

        <?= Html::saveButton() ?>

        <?php ActiveForm::end() ?>

    </div>
</div>
