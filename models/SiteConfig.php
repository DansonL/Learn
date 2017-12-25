<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "site_config".
 *
 * @property integer $id
 * @property string $config_name
 * @property string $config_value
 * @property integer $created
 * @property integer $updated
 */
class SiteConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_name', 'config_value', 'created', 'updated'], 'required'],
            [['config_value'], 'string'],
            [['created', 'updated'], 'integer'],
            [['config_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键id',
            'config_name' => '配置名称',
            'config_value' => '配置值',
            'created' => '创建时间',
            'updated' => '修改时间',
        ];
    }
}
