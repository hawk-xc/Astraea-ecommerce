<?php

namespace Spatie\LaravelIgnition\Solutions\SolutionProviders;

use Spatie\ErrorSolutions\SolutionProviders\Laravel\MissingLivewireComponentSolutionProvider as BaseMissingLivewireComponentSolutionProviderAlias;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;

class MissingLivewireComponentSolutionProvider extends BaseMissingLivewireComponentSolutionProviderAlias  implements HasSolutionsForThrowable
{

}
