@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-white">
        <div class="card-header border-b border-blueGray-200">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('cruds.question.title_singular') }}
                    {{ trans('global.list') }}
                </h6>

                @can('question_create')
                    <a class="btn btn-indigo" href="{{ route('questions.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.question.title_singular') }}
                    </a>
                @endcan
            </div>
        </div>
        @livewire('question.index')

    </div>
</div>
@endsection
