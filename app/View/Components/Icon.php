<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Icon extends Component
{
    public function __construct(
        public string $name,
        public string $class = 'w-4 h-4',
        public ?string $library = null,
    ) {
        $this->library = $library ?? config('project.icon_library', 'lucide');
    }

    public function render(): View|Closure|string
    {
        return view('components.icon');
    }
}
