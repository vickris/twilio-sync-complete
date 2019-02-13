@extends('layouts.app')

@section('content')
<voting :token="'{{ $token }}'"></voting>
@endsection
