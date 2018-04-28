<?php

namespace app\models;

use Yii;
use dektrium\user\models\User;

/**
 * This is the model class for table "peak_flow".
 *
 * @property int $id
 * @property int $user_id
 * @property int $value
 * @property string $recorded_at
 */
class PeakFlow extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'peak_flow';
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->recorded_at = date('Y-m-d H:i:s');

        if ( $this->user->id != Yii::$app->user->id && !Yii::$app->user->identity->isAdmin ) {
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value',], 'required'],
            [['user_id', 'value'], 'integer'],
            [['recorded_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'value' => 'Peak Flow (L/min)',
            'recorded_at' => 'Recorded At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
