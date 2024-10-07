<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SearchForm extends Component
{
    public $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function render()
    {
        return view('components.search-form');
    }
}
