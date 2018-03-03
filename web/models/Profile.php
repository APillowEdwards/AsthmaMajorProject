<?php
namespace app\models;

use dektrium\user\models\Profile as BaseProfile;

class Profile extends BaseProfile {

    public function rules()
    {
        $rules = parent::rules();

        // Rules for the extra fields here
        $rules['fieldRequired'] = ['dob', 'required'];

        return $rules;
    }

    public function attributeLabels()
    {
        $attribute_labels = parent::attributeLabels();

        $attribute_labels['dob'] = \Yii::t('user', 'Date of Birth');
        $attribute_labels['height'] = \Yii::t('user', 'Height');
        $attribute_labels['weight'] = \Yii::t('user', 'Weight');

        return $attribute_labels;
    }
}
