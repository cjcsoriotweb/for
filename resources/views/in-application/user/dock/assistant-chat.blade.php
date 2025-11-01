@extends('layouts.dock')

@section('title', __('Assistant IA').' - '.config('app.name'))



@section('dock-content')
    @livewire('chat-box', ['trainer' => 'default', 'title' => __('Assistant IA'), 'isOpen' => true])
@endsection
