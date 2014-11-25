<?php

namespace omnilight\tokens;

use omnilight\tokens\algorithms\AlgorithmInterface;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "yz_tokens".
 *
 * @property integer $id
 * @property string $type
 * @property string $name
 * @property string $token
 * @property string $created_at
 * @property string $expire_at
 *
 * @method void touch(string $attribute) Updates any attribute with timestamp behavior
 */
class Token extends \yz\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tokens}}';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => null,
                'value' => new Expression('NOW()'),
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name'], 'string', 'max' => 255],
            [['token'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('omnilight/tokens', 'ID'),
            'type' => Yii::t('omnilight/tokens', 'Type'),
            'name' => Yii::t('omnilight/tokens', 'Name'),
            'token' => Yii::t('omnilight/tokens', 'Token'),
            'created_at' => Yii::t('omnilight/tokens', 'Created'),
            'expire_at' => Yii::t('omnilight/tokens', 'Expire'),
        ];
    }

    /**
     * Creates token
     * @param string $type Type of the token
     * @param string $name Name of the token unique for selected type
     * @param callable|AlgorithmInterface $algorithm Algorithm of the token generation
     * @param int|\DateTime|Expression $expire Expiration date of the token
     * @return static
     */
    public static function create($type, $name, $algorithm = null, $expire = null)
    {
        $token = self::findOne(['type' => $type, 'name' => $name]);
        if ($token == null) {
            $token = new Token();
        }
        $token->type = $type;
        $token->name = $name;
        if (is_callable($algorithm)) {
            $token->token = call_user_func($algorithm);
        } else {
            $token->token = $algorithm->generate();
        }
        if (is_integer($expire)) {
            $token->expire_at = \DateTime::createFromFormat('U', $expire)->format('Y-m-d h:i:s');
        } elseif ($expire instanceof \DateTime) {
            $token->expire_at = $expire->format('Y-m-d h:i:s');
        } else {
            $token->expire_at = $expire;
        }
        $token->save();
        $token->touch('created_at');
        return $token;
    }

    /**
     * Returns token if it exists, otherwise - null
     * @param $type
     * @param $name
     * @return static|null
     */
    public static function exists($type, $name)
    {
        $token = self::findOne(['type' => $type, 'name' => $name]);

        if ($token === null)
            return null;

        if ($token->expire_at === null)
            return $token;

        if ((new \DateTime($token->expire_at)) < (new \DateTime())) {
            $token->delete();
            return null;
        }

        return $token;
    }

    /**
     * @param string $type
     * @param string $name
     * @param string $value
     * @return null|Token
     */
    public static function compare($type, $name, $value)
    {
        if (($token = self::exists($type, $name)) === null)
            return null;
        if ($token->token != $value)
            return null;
        return $token;
    }

    /**
     * @param $type
     * @param $name
     */
    public static function remove($type, $name)
    {
        self::deleteAll(['type' => $type, 'name' => $name]);
    }
}
