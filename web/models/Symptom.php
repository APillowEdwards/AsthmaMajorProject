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
}
