<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $patronymic
 * @property string $birthday
 * @property int $gender
 * @property string $email
 * @property string $phone
 * @property string $password
 *
 * @property Card[] $cards
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['birthday','first_name', 'last_name', 'patronymic','email','password'],'required', 'message' => 'Это поле не должно быть пустым'],
            [['birthday','first_name', 'last_name', 'patronymic','email','password'],'filter','filter' => 'trim'],
            [['birthday'], 'safe'],
            [['gender'], 'integer'],
            [['first_name', 'last_name', 'patronymic'], 'string', 'max' => 100,'min' =>3],
            [['email'], 'email','message' => 'Не корректный e-mail'],
            [['phone', 'password'], 'string','min' =>6,'message' => 'Это поле не должно быть менее 6 символов'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'patronymic' => 'Отчество',
            'birthday' => 'Дата рождения',
            'gender' => 'Пол',
            'email' => 'E - mail',
            //'phone' => 'Phone',
            'password' => 'Пароль'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCard()
    {
        return $this->hasOne(Card::className(), ['user_id' => 'id']);
    }
}
