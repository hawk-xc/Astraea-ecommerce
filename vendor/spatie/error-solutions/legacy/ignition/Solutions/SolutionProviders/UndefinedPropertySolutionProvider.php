<?php

namespace Spatie\Ignition\Solutions\SolutionProviders;

use Spatie\ErrorSolutions\SolutionProviders\UndefinedPropertySolutionProvider as BaseUndefinedPropertySolutionProviderAlias;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;

class UndefinedPropertySolutionProvider extends BaseUndefinedPropertySolutionProviderAlias implements HasSolutionsForThrowable
{

}
