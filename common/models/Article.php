<?php

namespace common\models;

use Yii;
use common\models\AdminUser;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property integer $article_id
 * @property string $title
 * @property string $description
 * @property integer $is_show
 * @property string $content
 * @property string $image
 * @property integer $add_time
 * @property integer $type
 * @property integer $created_by
 *
 * @property AdminUser $createdBy
 */
class Article extends \yii\db\ActiveRecord
{
    const ARTICLE_TYPE_MESSAGE = 1;
    const ARTICLE_TYPE_TERM = 2;
    const ARTICLE_TYPE_ANNOUNCE = 3;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['is_show', 'created_at', 'type', 'created_by'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => AdminUser::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => Yii::t('common', 'Article ID'),
            'title' => Yii::t('common', 'Title'),
            'description' => Yii::t('common', 'Description'),
            'is_show' => Yii::t('common', 'Is Show'),
            'content' => Yii::t('common', 'Content'),
            'created_at' => Yii::t('common', 'Created At'),
            'type' => Yii::t('common', 'Type'),
            'created_by' => Yii::t('common', 'Created By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'created_by']);
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
                if (Yii::$app->user->identity != null) {
                    $this->created_by = Yii::$app->user->identity->id;
                }
            }
            return true;
        } else {
            return false;
        }
    }
}
