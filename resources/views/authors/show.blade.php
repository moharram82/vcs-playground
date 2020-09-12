@extends('layouts.app')

@section('title', $author->name)

@section('content')

    <h1>{{ $author->name }}</h1>

    <h3>Articles</h3>

    <ul>
    @foreach($author->articles as $article)
        <li><a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a></li>
    @endforeach
    </ul>

@endsection
