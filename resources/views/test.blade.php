@extends('layouts.master')

@section('content')
    <p id="power">0</p>
@stop

@section('footer')
    <!-- <script src="{ { asset('js/socket.io.js') } }"></script> -->
    <script src="https://cdn.socket.io/socket.io-1.0.0.js"></script>
    <script>
        //var socket = io('http://localhost:3000');192.168.10.10
        var socket = io('http://localhost:3000');
        socket.on("test-channel:App\\Events\\EventName", function(message){
            // increase the power everytime we load test route
            $('#power').text(parseInt($('#power').text()) + parseInt(message.data.power));
        });
    </script>
@stop