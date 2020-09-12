@extends('layouts.app')

@section('title', 'Authors')

@section('content')

    <h1>Authors</h1>

    <ul>
        @foreach($authors as $author)
        <li><a href="{{ route('authors.show', $author) }}">{{ $author->name }}</a></li>
        @endforeach
    </ul>

@endsection
