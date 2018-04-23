<?php

namespace app\models;

use Yii;
use \DateTime;

/**
 * This is the model class for table "dose".
 *
 * @property int $id
 * @property int $medication_id
 * @property string $dose_size
 * @property string $taken_at
 *
 * @property Medication $medication
 */
class Dose extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dose';
    }

    public function beforeDelete() {
        if (!parent::beforeDelete()) {
            return false;
        }

        return true;
    }

    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medication_id', 'dose_size', 'taken_at'], 'required'],
            [['medication_id'], 'integer'],
            [['dose_size'], 'number'],
            [['taken_at'], 'filter', 'filter' => function ($value) {
                if ( $dt = DateTime::createFromFormat('d/m/Y H:i:s', $value) ) {
                    return $dt->format('Y-m-d H:i:s');
                }
                return false;
            }],
            [['taken_at'], 'safe'],
            [['medication_id'], 'exist', 'skipOnError' => true, 'targetClass' => Medication::className(), 'targetAttribute' => ['medication_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'medication_id' => 'Medication',
            'dose_size' => 'Dose Size',
            'taken_at' => 'Taken At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedication()
    {
        return $this->hasOne(Medication::className(), ['id' => 'medication_id']);
    }
}
