<?php

namespace Spatie\LaravelIgnition\Solutions\SolutionProviders;

use Spatie\ErrorSolutions\SolutionProviders\Laravel\UnknownMariadbCollationSolutionProvider as BaseUnknownMariadbCollationSolutionProviderAlias;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;

class UnknownMariadbCollationSolutionProvider extends BaseUnknownMariadbCollationSolutionProviderAlias implements HasSolutionsForThrowable
{

}
