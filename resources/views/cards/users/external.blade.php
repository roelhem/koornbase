

<b-card no-body>
    <h3 class="card-title" slot="header">Gekoppelde Accounts</h3>

    <table class="table card-table">
        <tbody>
        @foreach(\App\Enums\OAuthProvider::asOrderedArray('social') as $provider)
        <tr>
            <td class="w-1 p-2 px-3">
                <b-button size="sm" block class="text-left {{ $provider['isActive'] ? $provider['buttonClass'] : 'disabled btn-gray' }}"
                          href="{{ route('login.social',  ['$provider' => $provider['value']] ) }}">
                    <i class="fa {{ $provider['logoIcon'] }} mr-2"></i> {{ $provider['displayName'] }}
                </b-button>
            </td>
            <td>

                @if($provider['account'])
                <span>{{ $provider['account']->nickname ?? $provider['account']->email ?? $provider['account']->name }}</span>
                @else
                <em class="text-muted small">
                    @if($provider['isActive'])
                        [ Niet gekoppeld ]
                    @else
                        [ Momenteel niet mogelijk ]
                    @endif
                </em>
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

</b-card>