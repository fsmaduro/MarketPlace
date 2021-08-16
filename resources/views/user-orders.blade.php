@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-12">
            <h2>Meus Pedidos</h2>
            <hr>
        </div>

        <div class="col-12">
            <div class="accordion" id="accordionExample">
                @forelse($userOrders as $key => $order)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapseOne">
                            Pedido Número {{$order->reference}}
                        </button>
                        </h2>
                        <div id="collapseOne{{$key}}" class="accordion-collapse collapse @if($key == 0) show @endif" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            @php
                                $items = unserialize($order->items);
                            @endphp
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                        <th>Preço</th>
                                        <th>SubTotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $key => $item)
                                    <tr>
                                        <td>{{{$key}}}</td>
                                        <td>{{$item['name']}}</td>
                                        <td>{{number_format($item['amount'],2,',','.')}}</td>
                                        <td>R$ {{number_format($item['price'],2,',','.')}}</td>

                                        @php
                                            $subtotal = $item['price'] * $item['amount'];
                                            $total += $subtotal;
                                        @endphp

                                        <td>R$ {{number_format($subtotal,2,',','.')}}</td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="4">Total:</td>
                                        <td colspan="1">R$ {{number_format($total,2,',','.')}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning">Nenhum pedido efetuado!</div>
                @endforelse

            </div>
        </div>

        <div class="col-12">
            <hr>
            {{$userOrders->links()}}
        </div>

    </div>
@endsection
