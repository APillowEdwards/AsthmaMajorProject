<?php

namespace app\models;

use Yii;
use dektrium\user\models\User;
use yii\helpers\ArrayHelper;

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
            [['user_id', 'name', 'type', 'quantity', 'amount', 'unit'], 'required'],
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
            'quantity' => 'Quantity (e.g. number of puffs or tablets)',
            'amount' => 'Amount',
            'unit' => 'Unit (e.g. mg)',
        ];
    }

    public static function formatMedicationsForDropDown($medications)
    {
        return ArrayHelper::map( $medications, 'id', 'name', 'type' );
    }

    public static function formatTypesForDropDown()
    {
        return [
            'reliever' => 'Reliever',
            'preventer' => 'Preventer',
            'other' => 'Other',
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getDoses()
    {
        return $this->hasMany(Dose::className(), ['medication_id' => 'id'])->orderBy(['taken_at' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}
