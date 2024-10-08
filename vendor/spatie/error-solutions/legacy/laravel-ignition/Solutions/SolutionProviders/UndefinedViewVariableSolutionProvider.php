<?php

namespace Spatie\LaravelIgnition\Solutions\SolutionProviders;

use Spatie\ErrorSolutions\SolutionProviders\Laravel\UndefinedViewVariableSolutionProvider as BaseUndefinedViewVariableSolutionProviderAlias;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;

class UndefinedViewVariableSolutionProvider extends BaseUndefinedViewVariableSolutionProviderAlias implements HasSolutionsForThrowable
{

}
