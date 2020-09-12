@extends('layouts.app')

@section('title', 'Articles')

@section('content')

    <h1>Articles</h1>

    <ul>
        @foreach($articles as $article)
            <li><a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a> by <span class="text-muted">{{ $article->author->name }}</span></li>
        @endforeach
    </ul>

@endsection
