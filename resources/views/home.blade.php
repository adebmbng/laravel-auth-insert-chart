@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="container">
                            <h3> <b>Stock: </b> {{$data['goods'][0]->stock}} Gr</h3>
                            <div class="row">
                                <form action="{{ route('submit') }}" method="post">
                                    @if ($errors->any())
                                        <div class="alert alert-danger" role="alert">
                                            Please fix the following errors
                                        </div>
                                    @endif

                                    {!! csrf_field() !!}
                                    <div class="form-group{{ $errors->has('goods') ? ' has-error' : '' }}">
                                        <label for="goods">Goods</label> <br>
                                        <select name="goods">
                                            @foreach( $data['goods'] as $g )
                                                <option value="{{ $g->code }}"> {{ $g->name }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('Goods'))
                                            <span class="help-block">{{ $errors->first('goods') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('qty') ? ' has-error' : '' }}">
                                        <label for="qty">Quantity (in Gram)</label>
                                        <input type="number" class="form-control" id="qty" name="qty"
                                               placeholder="Quantity"
                                               value="{{ old('qty') }}">
                                        @if($errors->has('qty'))
                                            <span class="help-block">{{ $errors->first('qty') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('totalPrice') ? ' has-error' : '' }}">
                                        <label for="totalPrice">Total Price</label>
                                        <input type="number" min="1" step="any" class="form-control" id="totalPrice"
                                               name="totalPrice"
                                               placeholder="Total Price" value="{{ old('totalPrice') }}">
                                        @if($errors->has('totalPrice'))
                                            <span class="help-block">{{ $errors->first('totalPrice') }}</span>
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btn-default">Submit</button>
                                </form>
                            </div>
                        </div>

                        <div id="report" style="border: 1px solid #1b1e21; border-radius: 5px; padding: 25px; margin-top: 10px">
                            <button  v-on:click="yesterday()" class="btn btn-dark">Yesterday</button>
                            <button  v-on:click="today()" class="btn btn-dark">Today</button>
                            <div id="app">
                                {!! $chart->container() !!}
                            </div>
                        </div>
                        <div style="border: 1px solid #1b1e21; border-radius: 5px; padding: 25px; margin-top: 10px">
                            <div id="app2">
                                {!! $weekly->container() !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{ \Illuminate\Support\Facades\Log::info(json_encode($chart)) }}

    <script src="https://unpkg.com/vue"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/d3js/5.7.0/d3.min.js"></script>
    <script>

         var yesterday = new Vue({
            el: '#report',
            methods: {
                yesterday: function () {
                    location.href =  "{{route('yesterday')}}";
                },
                today: function () {
                    location.href =  "{{route('home')}}";
                }
            }
        });
    </script>
    <script>
        var app = new Vue({
            el: '#app',
        });
    </script>
    {!! $chart->script() !!}

    <script>
        var app2 = new Vue({
            el: '#app2',
        });
    </script>
    {!! $weekly->script() !!}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    {{--<script src="https://cdn.jsdelivr.net/npm/fusioncharts@3.12.2/fusioncharts.js" charset="utf-8"></script>--}}
    <script src=https://cdnjs.cloudflare.com/ajax/libs/echarts/4.0.2/echarts-en.min.js
            charset=utf-8>
    </script>

@endsection


