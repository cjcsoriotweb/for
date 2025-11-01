@extends('layouts.dock')

@section('title', __('Assistant IA').' - '.config('app.name'))



@section('dock-content')
    @livewire('ai.assistant-chat', ['prompt'=>'soit mechant'])
@endsection
