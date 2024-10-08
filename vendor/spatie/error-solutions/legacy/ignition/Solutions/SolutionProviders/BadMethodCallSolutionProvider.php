<?php

namespace Spatie\Ignition\Solutions\SolutionProviders;

use Spatie\ErrorSolutions\SolutionProviders\BadMethodCallSolutionProvider as BaseBadMethodCallSolutionProviderAlias;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;

class BadMethodCallSolutionProvider extends BaseBadMethodCallSolutionProviderAlias implements HasSolutionsForThrowable
{
}
