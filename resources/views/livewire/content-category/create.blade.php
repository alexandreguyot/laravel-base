<form wire:submit="submit" class="pt-3">

    <div class="form-group {{ $errors->has('contentCategory.name') ? 'invalid' : '' }}">
        <label class="form-label required" for="name">{{ trans('cruds.contentCategory.fields.name') }}</label>
        <input class="form-control" type="text" name="name" id="name" required wire:model="contentCategory.name">
        <div class="validation-message">
            {{ $errors->first('contentCategory.name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.contentCategory.fields.name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('contentCategory.slug') ? 'invalid' : '' }}">
        <label class="form-label" for="slug">{{ trans('cruds.contentCategory.fields.slug') }}</label>
        <input class="form-control" type="text" name="slug" id="slug" wire:model="contentCategory.slug">
        <div class="validation-message">
            {{ $errors->first('contentCategory.slug') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.contentCategory.fields.slug_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('content-categories.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>
