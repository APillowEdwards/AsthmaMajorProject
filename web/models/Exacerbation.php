<?php

namespace app\models;

use yii\helpers\ArrayHelper;
use dektrium\user\models\User;
use Yii;

/**
 * This is the model class for table "exacerbation".
 *
 * @property int $id
 * @property int $user_id
 * @property string $happened_at
 */
class Exacerbation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exacerbation';
    }

    /**
    * Ensure that the current user either owns the exacerbation or is an
    * administrator before deletion.
    */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        if ( $this->user->id != Yii::$app->user->id && !Yii::$app->user->identity->isAdmin ) {
            return false;
        }
        return true;
    }

    /**
    * Ensure that the current user either owns the exacerbation or is an
    * administrator before saving.
    */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ( !$this->happened_at ) {
            $this->happened_at = new \yii\db\Expression('NOW()');
        }

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
            [['user_id'], 'integer'],
            [['happened_at'], 'safe'],
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
            'happened_at' => 'Happened At',
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
