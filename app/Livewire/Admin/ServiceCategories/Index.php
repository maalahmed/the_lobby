<?php

namespace App\Livewire\Admin\ServiceCategories;

use App\Models\ServiceCategory;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $isActive = '';

    protected $queryString = ['search', 'isActive'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ServiceCategory::query()->with('parent');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%');
        }

        if ($this->isActive !== '') {
            $query->where('is_active', $this->isActive);
        }

        $serviceCategories = $query->orderBy('display_order')->paginate(20);

        return view('livewire.admin.service-categories.index', [
            'serviceCategories' => $serviceCategories,
        ])->layout('layouts.admin');
    }
}
