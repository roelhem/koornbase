<b-card no-body>

    <h3 class="card-title" slot="header">
        Mijn Persoonsgegevens
        &nbsp;
        <span class="text-muted small">
            (<data-display title="Naam">{{ $person->name }}</data-display>)
        </span>
    </h3>

    <table class="table card-table">
        <tbody>
        <tr>
            <th>Volledige Naam</th>
            <td class="tracking-wide">
                <data-display title="Initialen">{{ $person->name_initials }}</data-display>
                <span class="text-muted tracking-normal">[
                    <data-display title="Voornaam">{{ $person->name_first }}</data-display>
                    @if(!empty($person->name_middle))
                        &nbsp;
                        <data-display title="Overige voornamen">{{ $person->name_middle }}</data-display>
                    @endif
                    ]
                </span>
                <data-display title="Tussenvoegsel">{{ $person->name_prefix }}</data-display>
                <data-display title="Achternaam">{{ $person->name_last }}</data-display>
            </td>
        </tr>
        <tr>
            <th>Koornbeurs Bijnaam</th>
            <td class="tracking-wide">
                @if(!empty($person->name_nickname))
                    <data-display title="Bijnaam">{{ $person->name_nickname }}</data-display>
                @else
                    <data-display title="Korte naam">{{ $person->name_short }}</data-display>
                    &nbsp;
                    <em class="text-muted small tracking-normal">(Geen bijnaam ingesteld)</em>
                @endif
            </td>
        </tr>
        <tr>
            <th>Geboortedatum</th>
            <td>
                @if($person->birth_date)
                <data-display title="Geboortedatum">{{ $person->birth_date->formatLocalized('%e %B %Y') }}</data-display>
                &nbsp;
                <em class="text-muted">({{ $person->birth_date->age }} jaar)</em>
                @endif
            </td>
        </tr>
        <tr>
            <th>Status Lidmaatschap</th>
            <td>

                <display-person-membership-status :value="{{ $person->membership_status }}"
                                                  since="{{ $person->membership_status_since->format('c') }}">
                </display-person-membership-status>
            </td>
        </tr>
        <tr>
            <th>Koornbeurs-pas</th>
            <td>
                @if($person->cardOwnership)
                    <data-display class="tracking-wide" title="Nummer Koornbeurspas">
                        <i class="fa fa-id-card-o text-muted"></i> {{ $person->cardOwnership->card->id }}
                    </data-display>
                    @if($person->cardOwnership->start)
                        <small class="text-muted font-italic">
                            [ sinds
                                <data-display class="text-muted-dark" title="Koonbeurspas in bezit sinds">
                                    {{ $person->cardOwnership->start->formatLocalized('%e %B %Y') }}
                                </data-display>
                            ]
                        </small>
                    @endif
                @else
                    <small class="text-muted">(Geen Koornbeurs-pas geregistreerd)</small>
                @endif
            </td>
        </tr>
        @foreach($person->emailAddresses()->orderByDesc('is_primary')->orderBy('for_emergency')->orderBy('label')->get() as $email_address)
        <tr>
            <th>E-mailadres [{{ $email_address->label }}]</th>
            <td>
                <span class="tracking-wide">
                    <a href="mailto:{{ $person->name }} <{!! $email_address !!}>">{{ $email_address }}</a>
                </span>
                @if($email_address->is_primary)<small class="font-weight-normal font-italic text-primary mx-1">(primair)</small>@endif
                @if($email_address->for_emergency)<small class="font-weight-normal font-italic text-danger mx-1">(voor noodgevallen)</small>@endif
            </td>
        </tr>
        @endforeach
        @foreach($person->phoneNumbers()->orderByDesc('is_primary')->orderBy('for_emergency')->orderByDesc('is_mobile')->orderBy('label')->get() as $phone_number)
        <tr>
            <th>Telefoon [{{ $phone_number->label }}]</th>
            <td>
                <span class="tracking-wide">
                    <a href="tel:{{ $phone_number->phone_number->formatForMobileDialingInCountry('NL') }}">{{ $phone_number }}</a>
                </span>
                @if($phone_number->is_primary)<small class="font-weight-normal font-italic text-primary mx-1">(primair)</small>@endif
                @if($phone_number->for_emergency)<small class="font-weight-normal font-italic text-danger mx-1">(voor noodgevallen)</small>@endif
                @if($phone_number->is_mobile)<small class="font-weight-normal font-italic text-info mx-1">(mobiel)</small>@endif
            </td>
        </tr>
        @endforeach

        @inject('formatter', 'CommerceGuys\Addressing\Formatter\FormatterInterface')

        <?php
            $formatter->setLocale('NL');
        ?>

        @foreach($person->addresses()->orderByDesc('is_primary')->orderBy('for_emergency')->orderBy('label')->get() as $address)
        <tr>
            <th>Adres [{{ $address->label }}]</th>
            <td>
                <div>
                    @if($address->is_primary)<small class="font-weight-normal font-italic text-primary">(primair)</small>@endif
                    @if($address->for_emergency)<small class="font-weight-normal font-italic text-danger">(voor noodgevallen)</small>@endif
                </div>
                <div class="tracking-wide">
                    {!! $formatter->format($address) !!}
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

</b-card>
