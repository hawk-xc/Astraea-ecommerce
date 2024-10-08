<?php

namespace Spatie\LaravelIgnition\Solutions\SolutionProviders;

use Spatie\ErrorSolutions\SolutionProviders\Laravel\UndefinedLivewirePropertySolutionProvider as BaseUndefinedLivewirePropertySolutionProviderAlias;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;

class UndefinedLivewirePropertySolutionProvider extends BaseUndefinedLivewirePropertySolutionProviderAlias implements HasSolutionsForThrowable
{

}
