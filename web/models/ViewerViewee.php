<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "viewer_viewee".
 *
 * @property int $id
 * @property int $viewer_id
 * @property int $viewee_id
 * @property int $viewer_confirmed
 * @property int $viewee_confirmed
 */
class ViewerViewee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'viewer_viewee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['viewer_id', 'viewee_id', 'viewer_confirmed'], 'required'],
            [['viewer_id', 'viewee_id'], 'integer'],
            [['viewer_confirmed', 'viewee_confirmed'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'viewer_id' => 'Viewer ID',
            'viewee_id' => 'Viewee ID',
            'viewer_confirmed' => 'Viewer Confirmed',
            'viewer_confirmed' => 'Viewee Confirmed',
        ];
    }
}
