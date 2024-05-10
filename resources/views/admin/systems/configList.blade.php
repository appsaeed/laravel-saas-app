@foreach ($config as $key => $value)
    @if (gettype($value) == 'array' || gettype($value) == 'object')
        @include('admin.systems.configList', [ 'config' => $value])
    @else
        <tr>
            <td style="word-break: initial">
                {{ $key }}
            </td>
            <td>
                @if (gettype($value) == 'array' || gettype($value) == 'object')
                    {{ json_encode($value) }}
                @else
                    {{ $value }}
                @endif
            </td>
        </tr>
    @endif
@endforeach
