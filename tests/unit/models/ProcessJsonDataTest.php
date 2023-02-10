<?php

namespace unit\models;

use app\models\ProcessJsonData;
use Codeception\Test\Unit;

use Exception;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertArrayNotHasKey;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsArray;

class ProcessJsonDataTest extends Unit
{
    /**
     * @var \UnitTester
     */
    public $tester;
    /**
     * @var mixed
     */
    private $testDataSet;
    private ProcessJsonData $model;
    private $filename = __DIR__ . '\..\..\_data\NewsMockData.json';
    private $invalidMockData = __DIR__ . '\..\..\_data\InvalidMockData.json';

    protected function _before()
    {
        $testData = file_get_contents($this->filename);
        $this->testDataSet = json_decode($testData, true);
        $this->model = new ProcessJsonData();
    }

    public function testMakeRequestReturnsEmptyArrayIfPathNotGiven()
    {
        $data = $this->model->getNews();
        assertIsArray($data);
        assertEquals([], $data);
    }

    /**
     * @throws Exception
     * @group now
     */
    public function testMakeRequestReturnsDataIfValidPathGiven()
    {
        $data = $this->model->getNews('validPath');
        assertIsArray($data);
        assertEquals($this->testDataSet, $data);
    }

    /**
     * @throws Exception
     */
    public function testMakeRequestThrowsErrorIfDataReceivedIsInvalidJson()
    {
        $this->model->isValidJson = false;
        $this->expectException(Exception::class);
        $this->model->getNews('invalidPath');
    }

    public function testGetUniqueTickerReturnsUniqueTickers()
    {
        $data = $this->model->sanitizeData($this->testDataSet);
        $uniTickers = $this->model->getUniqueTickers($data);
        $jsonData = '{"ADT":1,"CRV":1,"CSH":1,"SOS":1,"ANIC":1,"AAL":1,"COPL":1,"SAA":1,"CGNR":1,"XSG":1,"NXT":1,"ARB":1,"CNKS":1,"RENX":1,"RNO":1,"PEN":1,"DELT":1,"TATE":1,"SDY":2,"HDD":1,"FUTR":1,"GRI":1,"LIO":1,"VEIL":1,"FGP":1,"ASHM":1,"PZC":1,"SVT":1,"JD.":1,"SKG":1,"DCC":1,"BEZ":1,"BDEV":1,"ESO":1}';
        $expectedArray = json_decode($jsonData, true);
        assertEquals($expectedArray, $uniTickers);
        assertEquals(34, count($uniTickers));
    }

    public function testSanitizeData()
    {
        $data = $this->testDataSet;
        foreach ($data as $item) {
            assertArrayHasKey('smw category', $item);
            assertArrayHasKey('post date', $item);
        }
        $sanitizedData = $this->model->sanitizeData($data);
        self::assertIsArray($sanitizedData);
        foreach ($sanitizedData as $item) {
            assertArrayNotHasKey('smw category', $item);
            assertArrayNotHasKey('post date', $item);
            assertArrayHasKey(ProcessJsonData::SMW_CATEGORY, $item);
            assertArrayHasKey(ProcessJsonData::POST_DATE, $item);
        }
    }

    public function testSanitizeDataReturnEmptyArrayIfInputIsEmptyArray()
    {
        $sanitizedData = $this->model->sanitizeData();
        assertIsArray($sanitizedData);
        assertEquals([], $sanitizedData);
    }

    public function testGetUniqueTickerReturnsUniqueTickersReturnEmptyArrayIfInputIsEmptyArray()
    {
        $sanitizedData = $this->model->getUniqueTickers();
        assertIsArray($sanitizedData);
        assertEquals([], $sanitizedData);
    }

    public function testGetMostPopularCategoryReturnsUniqueTickersReturnEmptyArrayIfInputIsEmptyArray()
    {
        $sanitizedData = $this->model->getMostPopularCategory();
        assertIsArray($sanitizedData);
        assertEquals([], $sanitizedData);
    }

    public function testGetTickerUnassignedMostPopularNewsCategoryReturnEmptyArrayIfInputIsEmptyArray()
    {
        $sanitizedData = $this->model->getTickerUnassignedMostPopularNewsCategory();
        assertIsArray($sanitizedData);
        assertEquals([], $sanitizedData);
    }

    public function testTickerUnassignedMostPopularCategory()
    {
        $data = $this->model->sanitizeData($this->testDataSet);
        $tickerUnassignedMostPopularCategory = $this->model->getTickerUnassignedMostPopularNewsCategory($data);
        assertEquals($tickerUnassignedMostPopularCategory,
            [
                ProcessJsonData::NAME => '<a href="/smw-category/tra">TRA</a>',
                ProcessJsonData::NO_OF_OCCURRENCE => 3
            ]);
    }

    public function testMostPopularTicker()
    {
        $data = $this->model->sanitizeData($this->testDataSet);
        $mostPopularCategory = $this->model->getMostPopularCategory($data);
        assertEquals($mostPopularCategory,
            [
                ProcessJsonData::NAME => '<a href="/smw-category/tra">TRA</a>',
                ProcessJsonData::NO_OF_OCCURRENCE => 19
            ]);
    }
}
