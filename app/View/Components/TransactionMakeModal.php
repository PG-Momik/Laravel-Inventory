<?php

namespace App\View\Components;

use App\Models\Category;
use Closure;
use FontLib\TrueType\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TransactionMakeModal extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public $categories)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.transaction-make-modal');
    }
}
