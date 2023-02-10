<?php

namespace app\controllers;

use app\models\ProcessJsonData;
use Exception;
use yii\captcha\CaptchaAction;
use yii\web\Controller;
use yii\web\ErrorAction;

class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @throws Exception
     */
    public function actionIndex()
    {
        $model = new ProcessJsonData();
        $path = "rest/views/stockmarketwire.json?display_id=smw_web_service";
        $news = $model->getNews($path);
        if ($news && is_array($news)) {
            $news = $model->sanitizeData($news);
            $mostPopularCategory = $model->getMostPopularCategory($news);
            $mostPopularTickerUnassignedCategory = $model->getTickerUnassignedMostPopularNewsCategory($news);
            $uniqueTicketCount = $model->getUniqueTickers($news);
        }

        $data = [
            'uniqueTickerCount' => $uniqueTicketCount ?? [],
            'mostPopularCategory' => $mostPopularCategory ?? [],
            'mostPopularTickerUnassignedCategory' => $mostPopularTickerUnassignedCategory ?? [],
            'news' => is_array($news) ? $news : [],
            'url' => $model->url,
        ];

        return $this->render('index', $data ?? []);
    }

}
