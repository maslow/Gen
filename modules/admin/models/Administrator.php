<?php

namespace app\modules\admin\models;

use app\modules\auth\models\U;
use Yii;

/**
 * This is the model class for table "{{%administrator}}".
 *
 * @property integer $uid
 * @property string $username
 * @property string $password
 * @property integer $created_at
 * @property string $created_ip
 * @property integer $created_by
 * @property U $u
 */
class Administrator extends \yii\db\ActiveRecord
{
    const U_SOURCE = 'administrator';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%administrator}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'created_at', 'created_ip', 'created_by'], 'required'],
            [['uid', 'created_at', 'created_by'], 'integer'],
            [['username'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 64],
            [['created_ip'], 'string', 'max' => 16],
            [['username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'username' => 'Username',
            'password' => 'Password',
            'created_at' => 'Created At',
            'created_ip' => 'Created Ip',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getU()
    {
        return $this->hasOne(U::className(), ['id' => 'uid']);
    }

    public function fields()
    {
        return [
            'uid',
            'username',
            'created_at',
            'created_ip',
            'created_by',
        ];
    }

    public function beforeValidate()
    {
        $this->created_at = time();
        $this->created_ip = isset(Yii::$app->request->userIP) ? Yii::$app->request->userIP : '0.0.0.0';

        if (isset(Yii::$app->user) && !Yii::$app->user->isGuest)
            $this->created_by = Yii::$app->user->id;
        else
            $this->created_by = 0;
        
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $u = new U();
            $u->source = self::U_SOURCE;
            if (!$u->save()) {
                $this->addError('uid', 'Failed to create uid for unknown reason');
                return false;
            }
            $this->uid = $u->id;
            if ($this->created_by === 0)
                $this->created_by = $u->id;

            $this->password = Yii::$app->security->generatePasswordHash($this->password);
        } elseif (array_key_exists('password', $this->getDirtyAttributes())) {
            $this->password = Yii::$app->security->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert);
    }
}
