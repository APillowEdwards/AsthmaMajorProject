<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "exacerbation_trigger".
 *
 * @property int $id
 * @property int $exacerbation_id
 * @property int $trigger_id
 */
class ExacerbationTrigger extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exacerbation_trigger';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exacerbation_id', 'trigger_id'], 'required'],
            [['exacerbation_id', 'trigger_id'], 'integer'],
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
            'trigger_id' => 'Trigger ID',
        ];
    }
}
