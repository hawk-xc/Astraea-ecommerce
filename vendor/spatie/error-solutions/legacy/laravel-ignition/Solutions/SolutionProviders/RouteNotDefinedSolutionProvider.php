<?php

namespace Spatie\LaravelIgnition\Solutions\SolutionProviders;

use Spatie\ErrorSolutions\SolutionProviders\Laravel\RouteNotDefinedSolutionProvider as BaseRouteNotDefinedSolutionProviderAlias;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;

class RouteNotDefinedSolutionProvider extends BaseRouteNotDefinedSolutionProviderAlias implements HasSolutionsForThrowable
{

}
