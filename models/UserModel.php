<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/13
 * Time: 10:44
 */

namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Query;

class UserModel extends ActiveRecord
{
    /**
     * @param $tableName
     * @param $data
     * @return bool
     * @throws Exception
     * @desc 单条插入数据
     */
    public function saveBaseInfo($tableName, $data)
    {
        if (empty($data) || empty($tableName)) {
            throw new Exception('参数为空');
        }
        Yii::$app->db->createCommand()->insert($tableName, $data)->execute();
        return true;
    }

    /**
     * @param $tableName
     * @param $items
     * @param $data
     * @throws Exception
     * @desc 批量插入数据
     */
    public function saveBatchData($tableName, $items, $data)
    {
        Yii::$app->db->createCommand()->batchInsert($tableName, $items, $data)->execute();
    }

    /**
     * @param $data
     * @param $tableName
     * @param $uid
     * @return array
     */
    public function getUserInfo($data, $tableName, $item, $value, $and = false, $item2 = "", $value2 = "")
    {
        if (!$and) {
            $userInfo = (new Query())->select($data)->from($tableName)->where($item . "='" . $value . "'")->all();
        } else {
            $userInfo = (new Query())->select($data)->from($tableName)->where($item . "='" . $value . "'" . " and "
            . $item2 . "='" . $value2 . "'")->all();
        }
        return $userInfo[0];
    }

    /**
     * @param $data
     * @param $tableName
     * @param $item
     * @param $value
     * @return array
     * @desc 获得批量数据
     */
    public function getBatchUserInfo($data, $tableName, $item, $value)
    {
        $userInfo = (new Query())->select($data)->from($tableName)->where($item . "='" . $value . "'")->all();
        return $userInfo;
    }

    /**
     * @param $tableName
     * @param $data
     * @throws Exception
     */
    public function updateInfoByUid($tableName, $data, $uid)
    {
        Yii::$app->db->createCommand()->update($tableName, $data, "uid={$uid}")->execute();
    }
}