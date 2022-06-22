@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header backgroundCustom text-white">Crear nueva order</div>
                <div class="card-body">
                    <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email">Nombre y apellido</label>
                                        <input type="text" class="form-control" placeholder="Juan Perez" name="customer_name" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email">E-mail </label>
                                        <input type="email" class="form-control" placeholder="juanperez@ejemplo.com" name="customer_email" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email">Numero de celular</label>
                                        <input type="text" class="form-control" placeholder="311 123 4567" name="customer_mobile" required>
                                    </div>
                                </div>
                            
                                
                            </div>
                    
                        </div>
                        <div class="modal-footer">
                        <button type="submit" class="btn backgroundCustomButton text-white mr-1 mt-3">Crear orden</button>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>
</div>

@endsection