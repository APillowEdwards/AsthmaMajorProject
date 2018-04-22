<?php

namespace app\models;

use dektrium\user\models\User;
use Yii;

/**
 * This is the model class for table "trigger".
 *
 * @property int $id
 * @property string $name
 */
class Trigger extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trigger';
    }

    /**
    * Ensure that the current user either owns the medication or is an
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
    * Ensure that the current user either owns the medication or is an
    * administrator before saving.
    */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
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
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
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
            'name' => 'Name',
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
