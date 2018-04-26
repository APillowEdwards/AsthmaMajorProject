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
 * @property integer $quantity // For example, 2 tablets
 * @property double $amount // For example '25'mg per tablet
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

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        return true;
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->user_id = Yii::$app->user->identity->id;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'quantity', 'amount', 'unit'], 'required'],
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
            'quantity' => 'Quantity (e.g. number of puffs or tablets)',
            'amount' => 'Amount',
            'unit' => 'Unit (e.g. mg)',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoses()
    {
        return $this->hasMany(Dose::className(), ['medication_id' => 'id']);
    }

    public static function formatMedicationsForDropDown($medications)
    {
        return ArrayHelper::map( $medications, 'id', 'name', 'type' );
    }
}
