@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Halo, {{ Auth::user()->name }} 👋</h1>
    <p>Selamat datang di halaman pengguna biasa.</p>
</div>
@endsection
