@if ($request->ckd_sql == 'S')
    <pre>
        {{$sql}}
    </pre>
@endif

@if ($request->ckd_pdf == 'S')
    @if(count($dados)>0)
        <table width="100%" style="border: 1px solid #f4f4f4; font-size: 10px; font-family: 'Open Sans', sans-serif">
            <tr style="border: 1px solid #f4f4f4;">
                @foreach ($campos as $key => $value)
                    <th style="border: 1px solid #f4f4f4;">
                        {{$key}}
                    </th>
                @endforeach
            </tr>
            @foreach ($dados as $key => $campos)
                <tr style="border: 1px solid #f4f4f4;">
                    @foreach ($campos as $value2)
                        <td style="border: 1px solid #f4f4f4;">{!!$value2!!}</td>
                    @endforeach
                </tr>
            @endforeach
        </table>
    @else
        Sem registros para informar
    @endif

@else

    @if(count($dados)>0)
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                @foreach ($campos as $key => $value)
                    <th>
                    {{$key}}
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach ($dados as $key => $campos)
                <tr>
                    @foreach ($campos as $value2)
                        <td>{!!$value2!!}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        Sem registros para informar
    @endif
@endif