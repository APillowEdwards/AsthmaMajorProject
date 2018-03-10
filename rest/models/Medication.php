<?php

namespace app\models;

use yii\helpers\ArrayHelper;
use dektrium\user\models\User;
use Yii;

/**
 * This is the model class for table "medication".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $type
 * @property double $amount
 * @property string $unit
 *
 * @property User $user
 */
class Medication extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medication';
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
            [['user_id', 'name', 'type', 'amount', 'unit'], 'required'],
            [['user_id'], 'integer'],
            [['name', 'type', 'unit'], 'string', 'max' => 255],
            [['amount'], 'number'],
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
            'user_id' => 'User',
            'name' => 'Name',
            'type' => 'Type',
            'amount' => 'Amount',
            'unit' => 'Unit (e.g. puff(s), mg)',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function formatMedicationsForDropDown($medications)
    {
        return ArrayHelper::map( $medications, 'id', 'name', 'type' );
    }
}
