<x-guest-layout>
    <!-- Barra superior -->
    <div class="container">
        
    </div>
    <div class="header row">
        <div class="logo span6 col">
            <h1><a href="">Droguería De La Ciudad</a></h1>
        </div>
        <div class="col-md-2 align-self-center">
            @if (Route::has('login'))
                <div class="hidden row">
                    @auth
                        <a href="{{ url('/home') }}" class="col justify-content-center">ingresar al panel de control</a>
                    @else
                        <a href="{{ route('login') }}" class="col justify-content-center">Ingresar</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="col justify-content-center">Registarse</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </div>
    <!-- Proximamente -->
    <div class="coming-soon">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <h2>Próximamente</h2>
                        <p>Proyecto de campo - AUSI 2021/22</p>
                        <div class="timer">
                            <div class="days-wrapper">
                                <span class="days"></span> <br>días
                            </div>
                            <div class="hours-wrapper">
                                <span class="hours"></span> <br>horas
                            </div>
                            <div class="minutes-wrapper">
                                <span class="minutes"></span> <br>minutos
                            </div>
                            <div class="seconds-wrapper">
                                <span class="seconds"></span> <br>segundos
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
