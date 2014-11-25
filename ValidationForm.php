<?php

namespace omnilight\tokens;

use yii\base\Model;


/**
 * Class ValidationForm
 * @property null|Token $tokenRecord
 */
class ValidationForm extends Model
{
    /**
     * @var string Type of the token
     */
    public $type;
    /**
     * @var string Name of the token
     */
    public $name;
    /**
     * @var string Token value entered by user
     */
    public $token;
    /**
     * @var string Label of the token
     */
    public $tokenLabel;
    /**
     * @var string Message shown when token is invalid
     */
    public $tokenInvalid;

    public function init()
    {
        if ($this->tokenLabel === null) {
            $this->tokenLabel = \Yii::t('omnilight/tokens', 'Token');
        }
        if ($this->tokenInvalid === null) {
            $this->tokenInvalid = \Yii::t('omnilight/tokens', 'Token is incorrect');
        }
    }

    public function rules()
    {
        return [
            [['token'], 'required'],
            [['token'], 'string'],
            [['token'], function() {
                if (Token::compare($this->type, $this->name, $this->token) == null) {
                    $this->addError('token', $this->tokenInvalid);
                }
            }]
        ];
    }

    public function attributeLabels()
    {
        return [
            'token' => $this->tokenLabel,
        ];
    }

    /**
     * @return null|Token
     */
    public function getTokenRecord()
    {
        return Token::compare($this->type, $this->name, $this->token);
    }
} 