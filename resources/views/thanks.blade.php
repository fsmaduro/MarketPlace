@extends('layouts.front')


@section('content')
    <h2 class="alert sucess">
        Muito Obrigado por sua compra!
    </h2>
    <h3>
        Seu pedido foi processado, código do pedido: {{request()->get('order')}}
    </h3>
@endsection
