<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/13
 * Time: 10:44
 */

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Exception;

class UserModel extends ActiveRecord
{
    /**
     * @param $tableName
     * @param $data
     * @return bool
     * @throws Exception
     * @desc $data总共有两个元素, 第一个元素表示要插入数据库的字段 第二个元素表示插入的数据
     */
    public function saveBaseInfo($tableName, $data)
    {
        if (empty($data) || empty($tableName)) {
            throw new Exception('参数为空');
        }
        $items = [];
        $insertData = [];
        foreach ($data as $key => $value) {
            $items[] = $key;
            $insertData[] = $value;
        }
        Yii::$app->db->createCommand()->batchInsert($tableName, $items, $insertData)->execute();
        return true;
    }
}