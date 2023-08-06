@extends('layouts.app')

@section('title', 'contact page')

@section('content')
    <h1>{{ $post['title'] }}</h1>
    <p>{{ $post['content'] }}</p>
@endsection

