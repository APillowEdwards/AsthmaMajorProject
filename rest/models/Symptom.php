<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "symptom".
 *
 * @property int $id
 * @property int $exacerbation_id
 * @property string $name
 * @property int $severity
 */
class Symptom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'symptom';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exacerbation_id', 'name', 'severity'], 'required'],
            [['exacerbation_id', 'severity'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['exacerbation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exacerbation::className(), 'targetAttribute' => ['exacerbation_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'exacerbation_id' => 'Exacerbation ID',
            'name' => 'Name',
            'severity' => 'Severity',
        ];
    }

    public static function possibleSymptomsAndOptions()
    {
        return [
            'Cough' => [1, 2, 3, 4, 5],
            'Wheeze' => [1, 2, 3, 4, 5],
            'Chest Tightness' => [1, 2, 3, 4, 5],
            'Shortness of Breath' => [1, 2, 3, 4, 5],
            'Awoken from Sleep' => false,
            'Preventing Usual Activities' => false,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExacerbation()
    {
        return $this->hasOne(Exacerbation::className(), ['id' => 'exacerbation_id']);
    }
}
