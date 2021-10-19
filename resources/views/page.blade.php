@extends('layouts.app')

@section('meta_title', $page->meta_title)
@section('meta_keywords', $page->meta_keywords)
@section('meta_description', $page->meta_description)
    
@section('content')
    
    <h1>{{ $page->title_h1 ?? $page->title }}</h1>
    
    {!! $page->content !!}
    
@endsection