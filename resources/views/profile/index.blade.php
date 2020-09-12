@extends('layouts.app')

@section('title', auth()->user()->name)

@section('content')

    <h1>{{ auth()->user()->name }}</h1>

    <a href="{{ route('profile.edit') }}" class="btn btn-primary mb-3">Update Profile</a>

    <h3>My articles</h3>

    <a href="{{ route('articles.create') }}" class="btn btn-primary mb-3">New Article</a>

    <ul>
        @foreach(auth()->user()->articles as $article)
            <li>
                <a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a>
                [<a href="{{ route('articles.edit', $article) }}">Edit</a>]
                [<a href="{{ route('articles.destroy', $article) }}">Delete</a>]
            </li>
        @endforeach
    </ul>

@endsection
