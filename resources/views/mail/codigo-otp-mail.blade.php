@extends('mail.layout')

@section('title', 'Código de Verificação')
    
@section('content')
    <h2>Seu código de verificação</h2>

    <p>Use o código abaixo para continuar:</p>

    <div class="codigo">{{ $codigo }}</div>

    <p>Se você não solicitou este código, ignore este e-mail.</p>
@endsection
