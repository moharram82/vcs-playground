@extends('layouts.app')

@section('title', $article->title)

@section('content')

    <h1>{{ $article->title }}</h1>

    <p class="text-muted">By {{ $article->author->name }}</p>
    <p class="text-muted">Published on {{ $article->created_at }}</p>

    {!! $article->body !!}

@endsection
