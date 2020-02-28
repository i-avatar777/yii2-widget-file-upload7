<?php

namespace iAvatar777\widgets\FileUpload7;

use app\models\Article;
use app\models\SiteUpdate;
use app\services\Subscribe;
use avatar\models\forms\Contact;
use avatar\services\LogReader;
use common\components\providers\ETH;
use common\components\sms\IqSms;
use common\models\avatar\Currency;
use common\models\avatar\UserBill;
use common\models\CompanyCustomizeItem;
use common\models\Land;
use common\models\PaymentBitCoin;
use common\models\UserAvatar;
use common\models\UserRegistration;
use common\payment\BitCoinBlockTrailPayment;
use common\services\Security;
use common\widgets\FileUpload7\FileUpload;
use cs\Application;
use cs\base\BaseController;
use cs\services\UploadFolderDispatcher;
use cs\services\VarDumper;
use cs\web\Exception;
use Yii;
use yii\base\UserException;
use yii\db\Connection;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class Upload2Controller extends \avatar\base\BaseController
{
    public $enableCsrfValidation = false;

    public function init()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    // https://learn.javascript.ru/xhr-crossdomain
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors'  => [
                    'Origin'                           => ['https://lesnaya-opushka.ru','https://www.lesnaya-opushka.ru'],
                    'Access-Control-Allow-Origin'      => ['https://lesnaya-opushka.ru','https://www.lesnaya-opushka.ru'],
                    'Access-Control-Request-Method'    => ['POST', 'OPTIONS'],
                    'Access-Control-Request-Headers'   => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 86400,
                    'Access-Control-Expose-Headers'    => [],
                ],
            ],
        ];
    }

    /**
     */
    public function actionFileUpload8()
    {
        if (Yii::$app->request->method == 'OPTIONS') return '';

        $model = new \avatar\models\validate\UploadControllerFileUpload8();
        $model->load(Yii::$app->request->post(), '');
        if (!$model->validate()) {
            return self::jsonErrorId(102, $model->errors);
        }

        return $model->action();
    }

    public function actionSessionProgress()
    {
        Yii::$app->session->open();

        if (!isset($_POST[ini_get('session.upload_progress.name')])) {
            return ['success' => false];
        }

        $key = ini_get('session.upload_progress.prefix') . $_POST[ini_get('session.upload_progress.name')];

        if (!isset($_SESSION[$key])) {
            return ['success' => false];
        }

        $progress = $_SESSION[$key];
        $pct = 0;
        $size = 0;

        if (is_array($progress)) {

            if (array_key_exists('bytes_processed', $progress) && array_key_exists('content_length', $progress)) {

                if ($progress['content_length'] > 0) {
                    $pct = round(($progress['bytes_processed'] / $progress['content_length']) * 100);
                    $size = round($progress['content_length'] / 1024);
                }
            }
        }

        return [
            'success' => true,
            'pct'     => $pct,
            'size'    => $size,
        ];
    }


}
