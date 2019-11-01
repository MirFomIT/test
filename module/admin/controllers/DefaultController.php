<?php

namespace app\module\admin\controllers;

use app\models\Card;
use app\models\User;
use yii\web\Controller;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    public $layout = 'admin';
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $not_active_cards = Card::find()->where(['activity_card' => 0])->all();

        return $this->render('index', compact('not_active_cards'));
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionActive()
    {
        $active_cards = Card::find()->where(['activity_card' => 1])->all();
        return $this->render('active',compact('active_cards'));
    }
}
