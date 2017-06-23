<?php

namespace Jenga\MyProject\Quotes\Library;
/**
 * Interface RatesInterface
 * @package Jenga\MyProject\Rates\Lib
 */
interface QuotesInterface
{
    /**
     * Do the rates calculation
     * @param QuotesBlueprint $blueprint
     * @return mixed
     */
    public function calculate(QuotesBlueprint $blueprint);

    /**
     * @param QuotesBlueprint $blueprint
     * @return mixed
     */
    public function getQuotes(QuotesBlueprint $blueprint);

}