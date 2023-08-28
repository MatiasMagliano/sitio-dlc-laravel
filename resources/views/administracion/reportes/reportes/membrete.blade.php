<div class="card">
    @isset($datos_membrete[0]['rango'])
        <div class="card-header text-center">
            <h5>{{ $datos_membrete[0]['rango'] }}</h5>
        </div>
    @endisset
    <div class="card-body">
        <table class="table-bordered" width="100%">
            <tr>
                <td width="20%">
                    <span>Fecha de emisión:</span> <br>
                    <strong>{{ $datos_membrete[0]['fecha_emision'] }}</strong>
                </td>
                <td width="70%">
                    <h1 class="text-center">Droguería de la Ciudad</h1>
                </td>
                <td rowspan="2" class="justify-content-end" width="10%">
                    <svg width="200px" height="100px" version="1.1" viewBox="0 0 93.417 56.287"
                        xmlns="http://www.w3.org/2000/svg">
                        <g transform="translate(-58.418 -10.973)">
                            <g transform="translate(25.309 -80.984)">
                                <rect x="34.022" y="92.848" width="91.567" height="41.665" fill="none"
                                    stroke="#01355d" stroke-width="1.7835" />
                                <g><text transform="scale(1.0385 .96294)" x="33.943794" y="133.69151" fill="#03335a"
                                        font-family="sans-serif" font-size="43.252px" letter-spacing="0px"
                                        stroke-width="1.0813" word-spacing="0px" style="line-height:1.25"
                                        xml:space="preserve">
                                        <tspan x="33.943794" y="133.69151" fill="#03335a" font-family="Lato"
                                            font-weight="900" stroke-width="1.0813">DLC</tspan>
                                    </text>
                                    <rect x="36.534" y="118.78" width="12.284" height="4.5357" fill="#fff" />
                                    <rect x="93.797" y="118.78" width="16.452" height="4.5357" fill="#fff" />
                                    <text transform="scale(1.0385 .96294)" x="31.181602" y="152.17464" fill="#03335a"
                                        font-family="sans-serif" font-size="9.936px" letter-spacing="0px"
                                        stroke-width=".2484" word-spacing="0px" style="line-height:1.25"
                                        xml:space="preserve">
                                        <tspan x="31.181602" y="152.17464" fill="#03335a" font-family="Lato"
                                            font-weight="900" stroke-width=".2484">Droguería La Ciudad</tspan>
                                    </text>
                                </g>
                            </g>
                        </g>
                    </svg>
                </td>
            </tr>
            <tr>
                <td width="20%">
                    <span>Hora de emisión: </span> <br>
                    <strong>{{ $datos_membrete[0]['hora_emision'] }}</strong>
                </td>
                <td>
                    <h3>
                        {{ $datos_membrete[0]['nombre_reporte'] }}
                    </h3>
                </td>
            </tr>
        </table>
    </div>
</div>
