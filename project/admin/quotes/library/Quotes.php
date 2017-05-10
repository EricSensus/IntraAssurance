<?php

namespace Jenga\MyProject\Quotes\Library;


class Quotes implements QuotesInterface
{

    /**
     * Do the rates calculation
     * @param QuotesBlueprint $blueprint
     * @return mixed
     */
    public function calculate(QuotesBlueprint $blueprint)
    {
        return $blueprint->calculate();
    }

    /**
     * @param QuotesBlueprint $blueprint
     * @return mixed
     */
    public function getQuotes(QuotesBlueprint $blueprint)
    {
        return $blueprint->getQuotes();
    }
}