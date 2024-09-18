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
    public $styleClass;
    public $keyStatus;
    public $key;
    public $bookingId;

    public function __construct($classClean, $classSvg, $cleanText, $roomNumber, $isClean, $styleClass, $keyStatus=false, $key="", $bookingId="")
    {
        $this->classClean = $classClean;
        $this->classSvg   = $classSvg;
        $this->cleanText  = $cleanText;
        $this->roomNumber = $roomNumber;
        $this->isClean    = $isClean;
        $this->styleClass = $styleClass;
        $this->keyStatus  = $keyStatus;
        $this->key        = $key; 
        $this->bookingId  = $bookingId;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.room-badge');
    }
}
