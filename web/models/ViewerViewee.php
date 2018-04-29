<?php

namespace app\models;

use Yii;
use dektrium\user\models\User;

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
            [['viewee_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['viewee_id' => 'id']],
            [['viewer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['viewer_id' => 'id']],
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
            'viewee_confirmed' => 'Viewee Confirmed',
            'viewer_confirmed' => 'Viewer Confirmed',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViewee()
    {
        return $this->hasOne(User::className(), ['id' => 'viewee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViewer()
    {
        return $this->hasOne(User::className(), ['id' => 'viewer_id']);
    }

}
