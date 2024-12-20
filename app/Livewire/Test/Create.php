<?php

namespace App\Livewire\Test;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Test;
use Livewire\Component;

class Create extends Component
{
    public Test $test;

    public array $listsForFields = [];

    public function mount(Test $test)
    {
        $this->test               = $test;
        $this->test->is_published = false;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.test.create');
    }

    public function submit()
    {
        $this->validate();

        $this->test->save();

        return redirect()->route('tests.index');
    }

    protected function rules(): array
    {
        return [
            'test.course_id' => [
                'integer',
                'exists:courses,id',
                'nullable',
            ],
            'test.lesson_id' => [
                'integer',
                'exists:lessons,id',
                'nullable',
            ],
            'test.title' => [
                'string',
                'nullable',
            ],
            'test.description' => [
                'string',
                'nullable',
            ],
            'test.is_published' => [
                'boolean',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['course'] = Course::pluck('title', 'id')->toArray();
        $this->listsForFields['lesson'] = Lesson::pluck('title', 'id')->toArray();
    }
}
