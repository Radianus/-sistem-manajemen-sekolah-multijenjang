<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectInput extends Component
{
    public $name;
    public $id;
    public $label;
    public $options; // Array asosiatif [value => text] atau Collection
    public $selected; // Nilai yang saat ini terpilih
    public $required;
    public $multiple; // Apakah ini select multiple
    public $size; // Untuk select multiple, berapa banyak opsi yang terlihat

    /**
     * Create a new component instance.
     */
    public function __construct(
        $name,
        $label = null,
        $options = [],
        $selected = null,
        $id = null,
        $required = false,
        $multiple = false,
        $size = null
    ) {
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->label = $label;
        $this->options = $options;
        $this->selected = $selected;
        $this->required = $required;
        $this->multiple = $multiple;
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.select-input');
    }
}
