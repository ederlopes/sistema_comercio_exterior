<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th></th>
            <th>Mínimo</th>
            <th>Máximo</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Taxa do prêmio</td>
            <td>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['PC_COB_MIN']}}%</td>
            <td>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['PC_COB_MAX']}}%</td>
        </tr>
        <tr>
            <td>Valor do prêmio comercial</td>
            <td>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['ID_MOEDA']}} {{$dadosPrecificacao['arrayValoresCoberturaMinimo']['VL_COBERTURA_IMP_FORMATDO']}}</td>
            <td>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['ID_MOEDA']}} {{$dadosPrecificacao['arrayValoresCoberturaMaximo']['VL_COBERTURA_IMP_FORMATDO']}}</td>
        </tr>
    </tbody>
</table>

<div class="alert alert-info">
    @foreach($dadosPrecificacao['arrayValoresCoberturaMinimo']['VALORES_RELATORIOS'] as $vl_relatorio) 
         @if ($dadosPrecificacao['arrayValoresCoberturaMinimo']['ID_MPME_MODALIDADE'] != '3' && $vl_relatorio->SIGLA_MOEDA == 'BRL')
            Tarifa de Análise de Crédito (Pré-Embarque): {{$vl_relatorio->SIGLA_MOEDA}} {{formatar_valor_sem_moeda($vl_relatorio->VALOR_PRODUTO)}}<br>
         @endif
         @if ($dadosPrecificacao['arrayValoresCoberturaMinimo']['ID_MPME_MODALIDADE'] != '1' && $vl_relatorio->SIGLA_MOEDA != 'BRL')
            Tarifa de Análise de Crédito (Pós-Embarque): {{$vl_relatorio->SIGLA_MOEDA}} {{formatar_valor_sem_moeda($vl_relatorio->VALOR_PRODUTO)}}<br>
        @endif
        @if(count($dadosPrecificacao['arrayValoresCoberturaMinimo']['VALORES_RELATORIOS']) == 1 && $dadosPrecificacao['arrayValoresCoberturaMinimo']['ID_MPME_MODALIDADE'] != '1')
            Tarifa de Análise de Crédito (Pós-Embarque) não encontrado favor entrar em contato com a ABGF.<br>
        @endif    
    @endforeach
</div>

<div class="alert alert-warning"><strong>Observação: </strong>O valor acima é apenas uma simulação, podendo ser recalculdado pela ABGF.</div>
@if ( env('APP_ENV') != 'production')
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div align="center"><h3>Mínimo</h3></div><br>

                <div class="col-md-2">
                    <label>% da PRIMEIRA Calculadora</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['VL_PERC_RETORNO_CALULADORA1']*100}}%</div>
                </div>

                <div class="col-md-2">
                    <label>Valor da PRIMEIRA Calculadora</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['VL_COBERTURA_RETORNO_CALULADORA1']}}</div>
                </div>


                <div class="col-md-2">
                    <label>% da SEGUNDA Calculadora</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['PERCENTUAL_CALCULADORA']}}%</div>
                </div>

                <div class="col-md-2">
                    <label>Valor da SEGUNDA Calculadora</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['VL_COBERTURA_IMP_FORMATDO']}}</div>
                </div>

                <div class="col-md-2">
                    <label>PC</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['PC']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_PIS</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['VALOR_PIS']}}</div>
                </div>
                <div style="height: 20px; clear: both" class="clear clearfix"></div>
                <div class="col-md-2">
                    <label>VALOR_COFINS</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['VALOR_COFINS']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_ISS</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['VALOR_ISS']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_IOF</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['VALOR_IOF']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_IIN</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['VALOR_IIN']}}</div>
                </div>

                <div class="col-md-2">
                    <label>RECEITA_LIQUIDA</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['RECEITA_LIQUIDA']}}</div>
                </div>

                <div class="col-md-2">
                    <label>LUCRO_BRUTO</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['LUCRO_BRUTO']}}</div>
                </div>
                <div style="height: 20px; clear: both" class="clear clearfix"></div>
                <div class="col-md-2">
                    <label>VALOR_CC</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['VALOR_CC']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_COR</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['VALOR_COR']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_DA</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['VALOR_DA']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_MS</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['VALOR_MS']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_LAIC</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['VALOR_LAIC']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_ID</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['VALOR_ID']}}</div>
                </div>
                <div style="height: 20px; clear: both" class="clear clearfix"></div>
                <div class="col-md-2">
                    <label>VALOR_IR</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['VALOR_IR']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_CS</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['VALOR_CS']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_LOL</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['VALOR_LOL']}}</div>
                </div>

                <div class="col-md-2">
                    <label>TOTAL</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['TOTAL']}}</div>
                </div>

                <div class="col-md-2">
                    <label>AUMENTO_PERC</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMinimo']['DADOS_CARREGAMENTO']['AUMENTO_PERC']}}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div align="center"><h3>Máximo</h3></div><br>

                <div class="col-md-2">
                    <label>% da PRIMEIRA Calculadora</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['VL_PERC_RETORNO_CALULADORA1']*100}}%</div>
                </div>

                <div class="col-md-2">
                    <label>Valor da PRIMEIRA Calculadora</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['VL_COBERTURA_RETORNO_CALULADORA1']}}</div>
                </div>


                <div class="col-md-2">
                    <label>% da SEGUNDA Calculadora</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['PERCENTUAL_CALCULADORA']}}%</div>
                </div>

                <div class="col-md-2">
                    <label>Valor da SEGUNDA Calculadora</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['VL_COBERTURA_IMP_FORMATDO']}}</div>
                </div>

                <div class="col-md-2">
                    <label>PC</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['PC']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_PIS</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['VALOR_PIS']}}</div>
                </div>
                <div style="height: 20px; clear: both" class="clear clearfix"></div>
                <div class="col-md-2">
                    <label>VALOR_COFINS</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['VALOR_COFINS']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_ISS</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['VALOR_ISS']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_IOF</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['VALOR_IOF']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_IIN</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['VALOR_IIN']}}</div>
                </div>

                <div class="col-md-2">
                    <label>RECEITA_LIQUIDA</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['RECEITA_LIQUIDA']}}</div>
                </div>

                <div class="col-md-2">
                    <label>LUCRO_BRUTO</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['LUCRO_BRUTO']}}</div>
                </div>
                <div style="height: 20px; clear: both" class="clear clearfix"></div>
                <div class="col-md-2">
                    <label>VALOR_CC</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['VALOR_CC']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_COR</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['VALOR_COR']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_DA</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['VALOR_DA']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_MS</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['VALOR_MS']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_LAIC</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['VALOR_LAIC']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_ID</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['VALOR_ID']}}</div>
                </div>
                <div style="height: 20px; clear: both" class="clear clearfix"></div>
                <div class="col-md-2">
                    <label>VALOR_IR</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['VALOR_IR']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_CS</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['VALOR_CS']}}</div>
                </div>

                <div class="col-md-2">
                    <label>VALOR_LOL</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['VALOR_LOL']}}</div>
                </div>

                <div class="col-md-2">
                    <label>TOTAL</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['TOTAL']}}</div>
                </div>

                <div class="col-md-2">
                    <label>AUMENTO_PERC</label>
                    <div>{{$dadosPrecificacao['arrayValoresCoberturaMaximo']['DADOS_CARREGAMENTO']['AUMENTO_PERC']}}</div>
                </div>
            </div>
        </div>
    </div>
@endif
