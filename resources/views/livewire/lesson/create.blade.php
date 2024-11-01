<form wire:submit="submit" class="pt-3">

    <div class="form-group {{ $errors->has('lesson.course_id') ? 'invalid' : '' }}">
        <label class="form-label required" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
        <x-select-list class="form-control" required id="course" name="course" :options="$this->listsForFields['course']" wire:model.live="lesson.course_id" />
        <div class="validation-message">
            {{ $errors->first('lesson.course_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.lesson.fields.course_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('lesson.title') ? 'invalid' : '' }}">
        <label class="form-label required" for="title">{{ trans('cruds.lesson.fields.title') }}</label>
        <input class="form-control" type="text" name="title" id="title" required wire:model="lesson.title">
        <div class="validation-message">
            {{ $errors->first('lesson.title') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.lesson.fields.title_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('mediaCollections.lesson_thumbnail') ? 'invalid' : '' }}">
        <label class="form-label" for="thumbnail">{{ trans('cruds.lesson.fields.thumbnail') }}</label>
        <x-dropzone id="thumbnail" name="thumbnail" action="{{ route('lessons.storeMedia') }}" collection-name="lesson_thumbnail" max-file-size="2" max-width="4096" max-height="4096" />
        <div class="validation-message">
            {{ $errors->first('mediaCollections.lesson_thumbnail') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.lesson.fields.thumbnail_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('lesson.short_text') ? 'invalid' : '' }}">
        <label class="form-label" for="short_text">{{ trans('cruds.lesson.fields.short_text') }}</label>
        <textarea class="form-control" name="short_text" id="short_text" wire:model="lesson.short_text" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('lesson.short_text') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.lesson.fields.short_text_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('lesson.long_text') ? 'invalid' : '' }}">
        <label class="form-label" for="long_text">{{ trans('cruds.lesson.fields.long_text') }}</label>
        <textarea class="form-control" name="long_text" id="long_text" wire:model="lesson.long_text" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('lesson.long_text') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.lesson.fields.long_text_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('mediaCollections.lesson_video') ? 'invalid' : '' }}">
        <label class="form-label" for="video">{{ trans('cruds.lesson.fields.video') }}</label>
        <x-dropzone id="video" name="video" action="{{ route('lessons.storeMedia') }}" collection-name="lesson_video" max-file-size="2" max-files="1" />
        <div class="validation-message">
            {{ $errors->first('mediaCollections.lesson_video') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.lesson.fields.video_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('lesson.position') ? 'invalid' : '' }}">
        <label class="form-label" for="position">{{ trans('cruds.lesson.fields.position') }}</label>
        <input class="form-control" type="number" name="position" id="position" wire:model="lesson.position" step="1">
        <div class="validation-message">
            {{ $errors->first('lesson.position') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.lesson.fields.position_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('lesson.is_published') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="is_published" id="is_published" wire:model="lesson.is_published">
        <label class="form-label inline ml-1" for="is_published">{{ trans('cruds.lesson.fields.is_published') }}</label>
        <div class="validation-message">
            {{ $errors->first('lesson.is_published') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.lesson.fields.is_published_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('lesson.is_free') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="is_free" id="is_free" wire:model="lesson.is_free">
        <label class="form-label inline ml-1" for="is_free">{{ trans('cruds.lesson.fields.is_free') }}</label>
        <div class="validation-message">
            {{ $errors->first('lesson.is_free') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.lesson.fields.is_free_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('lessons.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>
