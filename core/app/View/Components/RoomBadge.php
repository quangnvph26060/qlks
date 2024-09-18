<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RoomBadge extends Component
{
    public $classClean;
    public $classSvg;
    public $cleanText;
    public $roomNumber;
    public $isClean;

    public function __construct($classClean, $classSvg, $cleanText, $roomNumber, $isClean)
    {
        $this->classClean = $classClean;
        $this->classSvg = $classSvg;
        $this->cleanText = $cleanText;
        $this->roomNumber = $roomNumber;
        $this->isClean = $isClean;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.room-badge');
    }
}
