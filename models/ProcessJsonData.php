<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;


class ProcessJsonData extends Model
{
    const NEWS_CACHE_KEY = 'temporary_cache_key';
    const NEWS_CACHE_DURATION = 900; // 15 mins in seconds
    public bool $isValidJson = true;
    public string $url = 'https://www.ajbell.co.uk/';
    public const NO_OF_OCCURRENCE = 'noOfOccurrence';
    public const NAME = 'name';
    public const SMW_CATEGORY = 'smw_category';
    public const TICKER = 'ticker';
    public const TITLE = 'title';
    public const BODY = 'body';
    public const POST_DATE = 'post_date';
    public const TYPE = 'type';


    /**
     * @throws \Exception
     */
    public function makeRequest(string $path = '')
    {
        if (YII_ENV_TEST) {
            return $this->returnMockNews();
        }
        $handler = \curl_init();
        \curl_setopt($handler, CURLOPT_URL, $this->url . $path);
        \curl_setopt($handler, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($handler, CURLOPT_HEADER, 0);
        $responseFromRequest = \curl_exec($handler);

        if (\curl_errno($handler)) {
            $responseFromRequest = '';
        }
        // close curl
        \curl_close($handler);
        return json_decode($responseFromRequest, true) ?? $responseFromRequest;
    }


    public function getNews(string $path = ''): mixed
    {
        if (!$path) {
            return [];
        }
        try {
            return Yii::$app->cache->getOrSet(self::NEWS_CACHE_KEY, function () use ($path) {
                return $this->makeRequest($path);
            }, self::NEWS_CACHE_DURATION);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            Yii::error($errorMessage, __CLASS__ . ' ' . __METHOD__);
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function getUniqueTickers(array $news = []): array
    {
        $uniqueTickers = [];
        if (!count($news)) {
            return [];
        }

        foreach ($news as $subNews) {
            $ticker = ArrayHelper::getValue($subNews, self::TICKER);
            if ($ticker) {
                $uniqueTickers[] = $ticker;
            } else {
                $title = ArrayHelper::getValue($subNews, self::TITLE);
                Yii::error($title . " doesn't have ticker associated.", __CLASS__ . ' ' . __METHOD__);
            }
        }
        return array_count_values($uniqueTickers);
    }

    /**
     * @throws Exception
     */
    public function getTickerUnassignedMostPopularNewsCategory(array $news = []): array
    {
        if (!count($news)) {
            return [];
        }
        $tickerUnassignedNews = [];
        foreach ($news as $index => $subNews) {
            if (!ArrayHelper::getValue($subNews, self::TICKER)) {
                $tickerUnassignedNews[$index] = $subNews;
            }
        }

        return $this->getMostPopularCategory($tickerUnassignedNews);
    }


    public function getMostPopularCategory(array $data = []): array
    {
        if (!count($data)) {
            return [];
        }
        $categories = [];
        foreach ($data as $index => $news) {
            $categories[$index] = ArrayHelper::getValue($news, self::SMW_CATEGORY, '');
        }
        $countByCategory = array_count_values($categories);
        $highestOccurrenceCount = 0;
        $mostPopularCategory = '';
        foreach ($countByCategory as $key => $item) {
            if ($item > $highestOccurrenceCount) {
                $highestOccurrenceCount = $item;
                $mostPopularCategory = $key;
            }
        }
        return [self::NAME => $mostPopularCategory, self::NO_OF_OCCURRENCE => $highestOccurrenceCount];
    }

    /**
     * @param array $data
     * @return array
     */
    public function sanitizeData(array $data = []): array
    {
        if (!count($data)) {
            return [];
        }
        $sanitizedItem = [];
        foreach ($data as $index => $items) {
            if (is_array($items)) {
                foreach ($items as $key => $item) {
                    $newKey = str_replace(' ', '_', $key);
                    $sanitizedItem[$newKey] = $item;
                }
                $data[$index] = $sanitizedItem;
            }
        }
        return $data;
    }

    /**
     * @param $path
     * @return mixed
     * @throws \Exception
     */
    public function returnMockNews(): mixed
    {
        if ($this->isValidJson) {
            $fileName = __DIR__ . '\..\tests\_data\NewsMockData.json';
        } else {
            $fileName = __DIR__ . '\..\tests\_data\InvalidMockData.json';
        }
        $jsonData = file_get_contents($fileName);
        $result = json_decode($jsonData, true);
        if (json_last_error()) {
            throw new \Exception('Bad json data');
        }
        return $result;
    }
}