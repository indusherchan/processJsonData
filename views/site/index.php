<?php

/** @var yii\web\View $this */

/** @var app\models\ProcessJsonData $model */

use app\models\ProcessJsonData;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var $uniqueTickerCount */
/** @var $mostPopularCategory */
/** @var $mostPopularTickerUnassignedCategory */
/** @var $url */
/** @var $news */
$this->title = 'News List';


$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h2><?= Html::encode($this->title) ?></h2>

    <p class="lead" id="tickerCount">
        <?php
        echo "Unique ticker count: " . count($uniqueTickerCount); ?>
    </p>
    <p class="lead" id="mostPopularCategory">
        <?php
        echo "Most popular category: " .
            ArrayHelper::getValue($mostPopularCategory, ProcessJsonData::NAME, '') .
            " " .
            ArrayHelper::getValue($mostPopularCategory, ProcessJsonData::NO_OF_OCCURRENCE, '') .
            " occurrences.";
        ?>
    </p>
    <p class="lead" id="tickerUnassignedMostPopularCategory">
        <?php
        echo "Most popular category without ticker: " .
            ArrayHelper::getValue($mostPopularTickerUnassignedCategory, ProcessJsonData::NAME, '') .
            " " . ArrayHelper::getValue(
                $mostPopularTickerUnassignedCategory,
                ProcessJsonData::NO_OF_OCCURRENCE,
                '') . " occurrences.";
        ?>
    </p>

    <div class="body-content">

        <div class="row">
            <?php
            if (count($news)) {
                foreach ($news as $subNews) {
                    ?>
                    <div class="">
                        <h3><?= ArrayHelper::getValue($subNews, ProcessJsonData::TITLE, '') ?></h3>
                        <p><?= ArrayHelper::getValue($subNews, ProcessJsonData::BODY, '') ?></p>
                        <p>Ticker: <?= ArrayHelper::getValue($subNews, ProcessJsonData::TICKER, '') ?></p>
                        <p><?="Category: " . ArrayHelper::getValue($subNews, ProcessJsonData::SMW_CATEGORY, '') ?></p>
                        <p>Posted Date: <?= ArrayHelper::getValue($subNews, ProcessJsonData::POST_DATE, '') ?></p>
                        <p>News Type: <?= ArrayHelper::getValue($subNews, ProcessJsonData::TYPE, '') ?></p>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
