<?php

namespace App\Livewire\Lesson;

use App\Models\Course;
use App\Models\Lesson;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Edit extends Component
{
    public Lesson $lesson;

    public array $mediaToRemove = [];

    public array $listsForFields = [];

    public array $mediaCollections = [];

    public function addMedia($media): void
    {
        $this->mediaCollections[$media['collection_name']][] = $media;
    }

    public function removeMedia($media): void
    {
        $collection = collect($this->mediaCollections[$media['collection_name']]);

        $this->mediaCollections[$media['collection_name']] = $collection->reject(fn ($item) => $item['uuid'] === $media['uuid'])->toArray();

        $this->mediaToRemove[] = $media['uuid'];
    }

    public function getMediaCollection($name)
    {
        return $this->mediaCollections[$name];
    }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $this->lesson->id]));

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    public function mount(Lesson $lesson)
    {
        $this->lesson = $lesson;
        $this->initListsForFields();
        $this->mediaCollections = [

            'lesson_thumbnail' => $lesson->thumbnail,

            'lesson_video' => $lesson->video,

        ];
    }

    public function render()
    {
        return view('livewire.lesson.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->lesson->save();
        $this->syncMedia();

        return redirect()->route('lessons.index');
    }

    protected function rules(): array
    {
        return [
            'lesson.course_id' => [
                'integer',
                'exists:courses,id',
                'required',
            ],
            'lesson.title' => [
                'string',
                'required',
            ],
            'mediaCollections.lesson_thumbnail' => [
                'array',
                'nullable',
            ],
            'mediaCollections.lesson_thumbnail.*.id' => [
                'integer',
                'exists:media,id',
            ],
            'lesson.short_text' => [
                'string',
                'nullable',
            ],
            'lesson.long_text' => [
                'string',
                'nullable',
            ],
            'mediaCollections.lesson_video' => [
                'array',
                'nullable',
            ],
            'mediaCollections.lesson_video.*.id' => [
                'integer',
                'exists:media,id',
            ],
            'lesson.position' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'lesson.is_published' => [
                'boolean',
            ],
            'lesson.is_free' => [
                'boolean',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['course'] = Course::pluck('title', 'id')->toArray();
    }
}
