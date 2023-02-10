<?php

namespace functional;

class SiteControllerCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('site/index');
    }

    public function testIndexPageLoadsAndHasBasicContents(\FunctionalTester $I)
    {
        $I->see('News List', '.breadcrumb');
        $I->see('News List', 'h2');
        $I->see('Unique ticker count:', '#tickerCount');
        $I->see('Most popular category:', '#mostPopularCategory');
        $I->see('Most popular category without ticker:', '#tickerUnassignedMostPopularCategory');
    }

    public function testIndexPageHasNews(\FunctionalTester $I)
    {
        $I->seeElement('h3');
        $I->see('News List', 'h2');
        $I->see('Unique ticker count: 34', '#tickerCount');
        $I->see('19 occurrences.', '#mostPopularCategory');
        $I->see('TRA', '#mostPopularCategory');
        $I->see('TRA', '#tickerUnassignedMostPopularCategory');
        $I->see('3 occurrences', '#tickerUnassignedMostPopularCategory');
    }

}
