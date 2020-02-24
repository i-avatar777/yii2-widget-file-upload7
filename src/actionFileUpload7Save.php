<?php

namespace iAvatar777\widgets\FileUpload7;

/**
 * Created by PhpStorm.
 * User: s.arhangelskiy
 * Date: 08.08.2017
 * Time: 17:58
 */

use avatar\models\WalletETH;
use avatar\models\WalletToken;
use common\models\avatar\Currency;
use common\models\avatar\UserBill;
use common\models\BillingMain;
use common\models\comment\Comment;
use common\models\comment\CommentList;
use common\models\investment\IcoRequest;
use common\models\PaySystemConfig;
use common\models\piramida\Wallet;
use common\models\school\School;
use common\models\task\Task;
use common\models\Token;
use common\models\UserAvatar;
use common\models\UserWallet;
use common\services\Subscribe;
use cs\services\File;
use Yii;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\Response;

/**
 * Сохраняет файл в БД
 *
 * REQUEST:
 * + file - string - полный путь к файлу с доменом и без протокола
 * + school_id - int - идентификатор школы
 * + type_id - int - идентификатор типа файла
 * + size - int - размер файла
 * - update - array
 *
 * @return Response
 *
 * 400 Не загружены данные
 * 102 Ошибка валидации
 */
class actionFileUpload7Save extends \avatar\base\BaseAction
{

    public function run()
    {
        $model = new \avatar\models\validate\CabinetControllerFileUpload7Save();

        if (!$model->load(Yii::$app->request->post(), '')) {
            return self::jsonErrorId('400', 'Не загружены данные');
        }
        if (!$model->validate()) {
            return self::jsonErrorId('102', $model->errors);
        }
        $file = $model->action();

        return self::jsonSuccess(['file' => $file]);
    }
}