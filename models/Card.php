<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "card".
 *
 * @property int $id
 * @property string $number
 * @property int $pin
 * @property string $phone
 * @property int $user_id
 * @property int $shop_id
 * @property int $activity_card
 *
 * @property Shop $shop
 * @property User $user
 */
class Card extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'card';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number', 'pin', 'phone'],'required', 'message' => 'Это поле не должно быть пустым'],
            [['number', 'pin', 'phone'],'filter','filter' => 'trim'],
            [['number', 'phone'], 'string', 'max' => 20,'min' =>6],
            [[ 'pin','user_id', 'shop_id', 'activity_card'], 'integer'],
            [['shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shop::className(), 'targetAttribute' => ['shop_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Номер карты',
            'pin' => 'Пин код',
            'phone' => 'Телефон',
            'user_id' => 'Клиент',
            'shop_id' => 'Магазин',
            'activity_card' => 'Активировать карту',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
