<?php

namespace Spatie\LaravelIgnition\Solutions\SolutionProviders;

use Spatie\ErrorSolutions\SolutionProviders\Laravel\UndefinedLivewireMethodSolutionProvider as BaseUndefinedLivewireMethodSolutionProviderAlias;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;

class UndefinedLivewireMethodSolutionProvider extends BaseUndefinedLivewireMethodSolutionProviderAlias implements HasSolutionsForThrowable
{

}
