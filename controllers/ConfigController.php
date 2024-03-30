<?php
namespace humhub\modules\authAuthelia\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\authAuthelia\models\ConfigureForm;
use Yii;

/**
 * Module configuation
 */
class ConfigController extends Controller
{
    /**
     * Render admin only page
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new ConfigureForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            $this->view->saved();
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}