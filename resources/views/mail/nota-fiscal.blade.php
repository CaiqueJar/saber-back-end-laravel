@extends('mail.layout')

@section('title', 'Bem-vindo')
    
@section('content')
    <h2>Você realizou o pedido #{{ $pedido->codigo }}</h2>

    <p>
        Acesse sua nota fiscal aqui!
    </p>
    <br>
    <a href="{{ route('pdf.download', $pedido->id) }}" target="_blank">Baixar nota fiscal</a>
@endsection
