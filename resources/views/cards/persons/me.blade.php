<div class="card">



    <div class="card-header">
        <h3 class="card-title">
            Mijn Persoonsgegevens
            &nbsp;
            <span class="text-muted small">
                (<span class="data-display" title="Naam">{{ $person->name }}</span>)
            </span>
        </h3>
    </div>

    <table class="table card-table">
        <tbody>
        <tr>
            <th>Volledige Naam</th>
            <td class="tracking-wide">
                <span class="data-display" title="Initialen">{{ $person->name_initials }}</span>
                <span class="text-muted tracking-normal">[
                    <span class="data-display" title="Voornaam">{{ $person->name_first }}</span>
                    @if(!empty($person->name_middle))
                        &nbsp;
                        <span class="data-display" title="Overige voornamen">{{ $person->name_middle }}</span>
                    @endif
                    ]
                </span>
                <span class="data-display" title="Tussenvoegsel">{{ $person->name_prefix }}</span>
                <span class="data-display" title="Achternaam">{{ $person->name_last }}</span>
            </td>
        </tr>
        <tr>
            <th>Koornbeurs Bijnaam</th>
            <td class="tracking-wide">
                @if(!empty($person->name_nickname))
                    <span class="data-display" title="Bijnaam">{{ $person->name_nickname }}</span>
                @else
                    <span class="data-display text-muted" title="Korte naam">{{ $person->name_short }}</span>
                    &nbsp;
                    <em class="text-muted small tracking-normal">(Geen bijnaam ingesteld)</em>
                @endif
            </td>
        </tr>
        <tr>
            <th>Geboortedatum</th>
            <td>
                @if($person->birth_date)
                <span class="data-display" title="Geboortedatum">{{ $person->birth_date->formatLocalized('%e %B %Y') }}</span>
                &nbsp;
                <em class="text-muted">({{ $person->birth_date->age }} jaar)</em>
                @endif
            </td>
        </tr>
        <tr>
            <th>Status Lidmaatschap</th>
            <td>
                <span class="data-display tracking-wide" title="Status Lidmaatschap">
                    <membership-status :value="{{ $person->membership_status }}"></membership-status>
                </span>

                @if($person->membership_status > 0)
                <span class="text-muted small">
                    [ sinds
                    <span class="data-display text-muted-dark" title="Status Lidmaatschap Sinds">
                        {{ $person->membership_status_since->formatLocalized('%e %B %Y') }}
                    </span>
                    ]
                </span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Koornbeurs-pas</th>
            <td>
                @if($person->cardOwnership)
                    <span class="data-display tracking-wide" title="Nummer Koornbeurspas">
                        <i class="fa fa-id-card-o text-muted"></i> {{ $person->cardOwnership->card->id }}
                    </span>
                    @if($person->cardOwnership->start)
                        <small class="text-muted font-italic">
                            [ sinds
                                <span class="data-display text-muted-dark" title="Koonbeurspas in bezit sinds">
                                    {{ $person->cardOwnership->start->formatLocalized('%e %B %Y') }}
                                </span>
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

</div>
