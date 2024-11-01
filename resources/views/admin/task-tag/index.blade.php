@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-white">
        <div class="card-header border-b border-blueGray-200">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('cruds.taskTag.title_singular') }}
                    {{ trans('global.list') }}
                </h6>

                @can('task_tag_create')
                    <a class="btn btn-indigo" href="{{ route('task-tags.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.taskTag.title_singular') }}
                    </a>
                @endcan
            </div>
        </div>
        @livewire('task-tag.index')

    </div>
</div>
@endsection
