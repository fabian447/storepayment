
@extends('layouts.app')

@section('content')

<div class="container">
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
          <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </symbol>
        <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
        </symbol>
        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
        </symbol>
      </svg>
     @if (session('status'))
        <div class="alert alert-warning d-flex align-items-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
        <div>
            {{ session('status') }}
        </div>
      </div>
      @endif
    <div class="row">
        <div class="col-4">
            <div class="card backgroundCustom text-white">
                <div class="card-body">
                    <h4><b>Datos de la orden:</b></h4>
                    <div>
                        <b class="textSm">Nombre Cliente:</b>
                        <p class="textLight">{{$order->customer_name}}</p>
                    </div>
                    <div>
                        <label for=""><b  class="textSm">Email Cliente:</b></label>
                        <p class="textLight">{{$order->customer_email}}</p>
                    </div>
                    <div>
                        <label for=""><b  class="textSm">Celular Cliente:</b></label>
                        <p class="textLight">{{$order->customer_mobile}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card paymentStatus">
                <div class="card-body">
                    <label for=""><h4><b>Estado del pago:</b></h4></label>
                
                        @if($payment == null)
                        <div class="alert alert-dark" role="alert">
                            Pago no iniciado
                        </div>
                        @elseif($payment->status == config('payments.statuses.approved'))
                            <div class="alert alert-success" role="alert">
                               Aprobado
                            </div>                          
                        @elseif($payment->status == config('payments.statuses.rejected'))
                            <div class="alert alert-danger" role="alert">
                                Rechazado
                            </div> 
                        @elseif($payment->status == config('payments.statuses.expired'))
                            <div class="alert alert-danger" role="alert">
                                Expirado
                            </div>   
                        @else
                            <div class="alert alert-warning" role="alert">
                                Pendiente
                            </div>                          
                        @endif

                        <div>
                            <label for=""><h4><b>Monto a pagar:</b></h4></label>
                            <p class="textLight textLg">100$</p>
                        </div>

                        @if($payment == null || $payment->status != config('payments.statuses.approved'))
                        <img src="{{asset('img/placetopay.png')}}" width="250px">
                        <form action="{{ route ('payments.pay', ['order_id' => $order->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <button type="submit" class="btn backgroundCustomButton text-white mr-1 mt-3">Proceder al pago</button>
                        </form>
                        @endif
                </div>
            </div>
        </div>
    </div>
  </div>




   

@endsection