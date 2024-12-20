<?php

namespace App\Livewire\ProductTag;

use App\Models\ProductTag;
use Livewire\Component;

class Edit extends Component
{
    public ProductTag $productTag;

    public function mount(ProductTag $productTag)
    {
        $this->productTag = $productTag;
    }

    public function render()
    {
        return view('livewire.product-tag.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->productTag->save();

        return redirect()->route('product-tags.index');
    }

    protected function rules(): array
    {
        return [
            'productTag.name' => [
                'string',
                'required',
            ],
        ];
    }
}
