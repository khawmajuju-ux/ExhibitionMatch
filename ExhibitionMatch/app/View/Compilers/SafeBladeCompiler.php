<?php

namespace App\View\Compilers;

use Illuminate\View\Compilers\BladeCompiler;

class SafeBladeCompiler extends BladeCompiler
{
    /**
     * Compile the given Blade template contents.
     *
     * We defensively reset the @forelse/@empty counter to avoid cases where a
     * stale negative counter causes invalid PHP like "$__empty_-1".
     */
    public function compileString($value)
    {
        // This property comes from Illuminate\View\Compilers\Concerns\CompilesLoops.
        $this->forElseCounter = 0;

        return parent::compileString($value);
    }

    /**
     * Compile the view at the given path.
     *
     * We also reset the counter here to ensure it's fresh for each file compilation.
     */
    public function compile($path = null)
    {
        $this->forElseCounter = 0;

        return parent::compile($path);
    }
}

