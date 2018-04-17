<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_token".
 *
 * @property int $id
 * @property string $token
 * @property int $user_id
 */
class AuthToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['token', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['token'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'user_id' => 'User ID',
        ];
    }
}
