<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверные данные.');
                $session = \Yii::$app->session;
                $session->open();
                if (!isset($session['err_login'])) $session['err_login'] = 0;
                $count = $session['err_login'];
                $count++;
                $session['err_login'] = $count;
                if ($session['err_login']>2){
                    $session['timeblocked'] = date('U');
                }
            }
        }
    }

    public function issetBlock($session = null)
    {
        if (isset($session['timeblocked']) && (date('U') - $session['timeblocked'] < 300)){
            $this->addError('timeblocked','Попробуйте еще раз через '.(300 - (date('U') - $session['timeblocked'])).' секунд.');
            return true;
        } else {
            $this->clearErrors('timeblocked');
            if (isset($session['timeblocked']) && (date('U') - $session['timeblocked'] > 300)){
                unset($session['timeblocked']);
                unset($session['err_login']);
            }
            return false;
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser());
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
