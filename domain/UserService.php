<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/4
 * Time: 11:40
 */
namespace app\domain;
use app\models\UserModel;
use Flogger;
use Util;
use BsEnum;

class UserService
{
    /**
     * @author wenkaikai
     * @param $checkFiled
     * @param $params
     * @return bool
     */
    public function checkParams($checkFiled, $params)
    {
        foreach ($checkFiled as $value) {
            if (!isset($params[$value]) || empty($params[$value])) {
                Flogger::warning("缺少参数: " . $value);
                return false;
            }
        }
        return true;
    }

    /**
     * @param $params
     * @param $failCode
     * @return bool
     * @desc 校验登录参数是否合法
     */
    public function checkLoginParams($params, &$failCode)
    {
        $mobile = $params['userPhone'];
        $userName = $params['userName'];
        $password = $params['password'];
        $email = $params['userEmail'];
        $retCheckMobile = Util::isValidMobile($mobile);
        //校验用户手机号
        if (!$retCheckMobile) {
             $failCode = BsEnum::UN_VALID_MOBILE;
             return false;
        }
        //校验用户名
        $retCheckUserName = Util::isValidUserName($userName);
        if (!$retCheckUserName) {
            $failCode = BsEnum::UN_VALID_USERNAME;
            return false;
        }
        //校验密码是否正确
        $retCheckPassword = Util::isVaildPassword($password);
        if ($retCheckPassword === BsEnum::UN_VALID_PASSLEN) {
            $failCode = BsEnum::UN_VALID_PASSLEN;
            return false;
        } elseif ($retCheckPassword === BsEnum::UN_VALID_PASSWORD) {
            $failCode = BsEnum::UN_VALID_PASSWORD;
            return false;
        }
        //校验邮箱是否合法
        $retCheckEmail = Util::isVaildEmail($email);
        if (!$retCheckEmail) {
            $failCode = BsEnum::UN_VALID_EMAIL;
            return false;
        }
        return true;
    }

    /**
     * @param $account
     * @desc 判断登录时用户输入的类型(电话号, 邮箱, 用户名)
     */
    public function judjeLoginType(&$params)
    {
        $account = $params['account'];
        if (is_numeric($account)) {
            $params['mobile'] = $account;
            $params['userType'] = BsEnum::MOBILE;
        } elseif (strstr($account, '@')) {
            $params['email'] = $account;
            $params['userType'] = BsEnum::EMAIL;
        } else {
            $params['name'] = $account;
            $params['userType'] = BsEnum::USERNAME;
        }
        unset($params['account']);
    }

    /**
     * @param $params
     * @throws \yii\db\Exception
     */
    public function saveUserBaseInfo($params)
    {
        $table = "user_base";
        $saveData = $this->formUserData($params);
        $saveRes = (new UserModel())->saveBaseInfo($table, $saveData);
        return $saveRes;
    }

    /**
     * @param $uid
     * @return array
     */
    public function getUserInfoByUid($uid)
    {
        $table = "user_base";
        $data = ['user_name', 'password', 'mobile', 'email', 'salt'];
        $userInfo = (new UserModel())->getInfoByUid($data, $table, $uid);
        return $userInfo;
    }

    /**
     * @desc 格式化用户信息
     */
    private function formUserData($params)
    {
        $saveData = [
            'uid'       => $params['uid'],
            'user_name' => $params['userName'],
            'password'  => $params['password'],
            'mobile'    => $params['userPhone'],
            'email'     => $params['userEmail'],
            'salt'      => $params['salt'],
            'update_time' => time(),
        ];
        return $saveData;
    }
}