# Model `Person` Documentatie (Voor REST-API)

Het model `Person` modelleert een persoon die *O.J.V. de Koornbeurs* als **vereniging** kent. Dit zijn bijvoorbeeld 
kennismakers, leden en oud-leden, maar ook vaste gasten, zaalhuurders, zakelijke relaties, inhuurkrachten, etc.
Het is de bedoeling dat iedereen die te maken heeft met meer dan één lid van de Koornbeurs één (en slechts één) 
`Person`-model heeft.

Om dit doel te bereiken heeft de `Person` model bijna geen verplichte velden. (Alleen het veld `name` is een
verplicht veld die niet automatisch gegenereerd wordt.) Dit kan onhandig zijn bij het ontwikkelen van applicaties
omdat veel velden van `Person` leeg kunnen zijn. Daarnaast is het zo dat actieve leden ook worden gemodelleerd met een
`Person`.

Verder is deze model zo ingericht dat het gemakkelijk uitgebreid kan worden als er meer gegevens blijken nodig te zijn
van de persoon. Ook is geprobeerd om het mogelijk te maken om gevoelige gegevens te verwijderen zonder dat de
database daar te veel onder leid.

Je zou kunnen zeggen dat in het basis-syteem dit model het meest centraal staat en het belangrijkst is. Het is 
daarom aan te raden om dit model goed te begrijpen als je een applicatie maakt met de API, zelfs al speelt dit model
geen grote rol in jouw specifieke toepassing.

## Structuur Samenvatting

De hoofd gegevens van een `Person` worden opgeslagen in de tabel `persons` van de database. Het *php*-gedeelte
van het systeem gebruikt de class `\App\Person` om dit model te manipuleren. In de tabel `persons` zelf worden niet
zo veel gegevens opgeslaten. De meeste gegevens van een persoon worden gedefinieerd in gerelateerde tabellen.

## 1. Attributen

### Opgeslagen attributen

<table>
    <thead>
        <tr>
            <th>Naam</th>
            <th>Type</th>
            <th>Omschrijving</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>id</code></td>
            <td><tt>integer</tt></td>
            <td>De primaire sleutel van een <code>Person</code> model.</td>
        </tr>
        <tr>
            <td><code>name</code></td>
            <td><tt>string</tt></td>
            <td>De volledige naam van de persoon.</td>
        </tr>
        <tr>
            <td><code>name_short</code></td>
            <td><tt>string</tt> | <tt>copy</tt> <code>name</code></td>
            <td>Een korte naam (Bijvoorbeeld alleen de voornaam.)</td>
        </tr>
        <tr>
            <td><code>name_formal</code></td>
            <td><tt>string</tt> | <tt>null</tt></td>
            <td>De formele naam van de persoon</td>
        </tr>
        <tr>
            <td><code>nickname</code></td>
            <td><tt>string</tt> | <tt>null</tt></td>
            <td>De bijnaam van de persoon (in de Koornbeurs.)</td>
        </tr>
        <tr>
            <td><code>birth_date</code></td>
            <td><tt>date-string</tt> | <tt>null</tt></td>
            <td>De geboortedatum van de persoon.</td>
        </tr>
        <tr>
            <td><code>remarks</code></td>
            <td><tt>string</tt> | <tt>null</tt></td>
            <td>Plek voor eventuele opmerkingen.</td>
        </tr>
    </tbody>
</table>

### Berekende attributen (uit opgeslagen attributen)

<table>
    <thead>
        <tr>
            <th>Naam</th>
            <th>Type</th>
            <th>Afhankelijk van</th>
            <th>Omschrijving</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>age</code></td>
            <td><tt>integer</tt> | <tt>null</tt></td>
            <td><code>birth_date</code></td>
            <td>De leeftijd van de persoon op dit moment.</td>
        </tr>
    </tbody>
</table>

The kolom **afhankelijk van** geeft aan welke attributen worden gebruikt om de waarde van de berekende 
attribuut te bepalen.

## 2. Relaties

### Overzicht
<table>
    <thead>
        <tr>
            <th>Soort</th>
            <th>Naam</th>
            <th>Gerelateerd Met</th>
            <th>Inverse</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><em>'Heeft meerdere'</em></td>
            <td><code>users</code></td>
            <td><code>User</code></td>
            <td><code>person</code></td>
        </tr>
        <tr>
            <td><em>'Heeft meerdere'</em></td>
            <td><code>addresses</code></td>
            <td><code>PersonAddress</code></td>
            <td><code>person</code> <em>(Zwak)</em></td>
        </tr>
        <tr>
            <td><em>'Heeft meerdere'</em></td>
            <td><code>emailAddresses</code></td>
            <td><code>PersonEmailAddress</code></td>
            <td><code>person</code> <em>(Zwak)</em></td>
        </tr>
        <tr>
            <td><em>'Heeft meerdere'</em></td>
            <td><code>phoneNumbers</code></td>
            <td><code>PersonPhoneNumber</code></td>
            <td><code>person</code> <em>(Zwak)</em></td>
        </tr>
        <tr>
            <td><em>'Heeft meerdere'</em></td>
            <td><code>memberships</code></td>
            <td><code>Membership</code></td>
            <td><code>person</code> <em>(Zwak)</em></td>
        </tr>
        <tr>
            <td><em>'Heeft meerdere'</em></td>
            <td><code>debtors</code></td>
            <td><code>Debtor</code></td>
            <td><code>person</code></td>
        </tr>
        <tr>
            <td><em>'Heeft meerdere'</em></td>
            <td><code>cards</code></td>
            <td><code>KoornbeursCard</code></td>
            <td><code>owner</code></td>
        </tr>
        <tr>
            <td><em>'Hoort bij meerdere'</em></td>
            <td><code>groups</code></td>
            <td><code>Group</code></td>
            <td><code>persons</code></td>
        </tr>
    </tbody>
</table>

Als er *(Zwak)* achter een relation staat betekend dat dat het model die deze relatie heeft wordt verwijderd
als het gerelateerde model wordt verwijderd. (In dit geval bijvoorbeeld verdwijnen alle `PersonEmailAddress`
-gegevens als the `Person` van deze addressen wordt verwijderd.)

### Relatie met `MembershipStatusChange`.

Een person heeft een relatie met de 'niet echt bestaande' table `membership_status_changes`. De waarde van deze
tabel wordt bepaald door de gegevens in `memberships` anders te ordenen. Dit is gedaan om het makkelijk te maken
wat de huidige lidstatus is van een `Person`.

Er zijn enkele relaties gedefinieerd om deze waarde te bepalen. Deze kunnen alleen niet aangeroepen vanuit de
REST-API. Wel heeft `Person` een berekend attribuut met de naam `membership_status` die alle informatie geeft
over de huidige lidstatus van de `Person`.

`membership_status` heeft als waarde een object met daarin de volgende gegevens:

<table>
    <thead>
        <tr>
            <th>Naam</th>
            <th>Type</th>
            <th>Omschrijving</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>membership_status.status</code></td>
            <td><tt>integer</tt></td>
            <td>De waarde van de huidige lidstatus.</td>
        </tr>
        <tr>
            <td><code>membership_status.name</code></td>
            <td><tt>string</tt></td>
            <td>De naam van de huidige lidstatus.</td>
        </tr>
        <tr>
            <td><code>membership_status.label</code></td>
            <td><tt>string</tt></td>
            <td>De Nederlandse naam voor de lidstatus.</td>
        </tr>
        <tr>
            <td><code>membership_status.since</code></td>
            <td><tt>date-string</tt> | <tt>undefined</tt></td>
            <td>De datum wanneer de lidstatus is veranderd naar de huidge status.</td>
        </tr>
    </tbody>
</table>

Er zijn in totaal 4 verschillende soorten lid-statussen. De onderstaande tabel laat zien hoe deze statussen
worden gerepresentateerd.

<table>
    <thead>
        <tr>
            <th>Label</th>
            <th>Naam</th>
            <th>Integer Waarde</th>
            <th>Omschrijving</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Buitenstaander</td>
            <td><code>Outsider</code></td>
            <td><code>0</code></td>
            <td>Nog nooit ingeschreven bij de Koornbeurs.</td>
        </tr>
        <tr>
            <td>Kennismaker</td>
            <td><code>Novice</code></td>
            <td><code>1</code></td>
            <td>Heeft zich wel ingeschreven bij de Koornbeurs, maar zit nog in de proefperiode.</td>
        </tr>
        <tr>
            <td>Lid</td>
            <td><code>Member</code></td>
            <td><code>2</code></td>
            <td>Is een volledig lid van de Koornbeurs.</td>
        </tr>
        <tr>
            <td>Oud-Lid</td>
            <td><code>FormerMember</code></td>
            <td><code>3</code></td>
            <td>Is ooit lid geweest van de Koornbeurs, maar heeft zijn lidmaatschap (of inschrijving) opgezegd.</td>
        </tr>
    </tbody>
</table>

## 3. REST-API endpoints

De API geeft als belangrijkste endpoint(s) het pad `/api/persons`. Deze endpoints zorgen voor de meeste elementaire 
CRUD-bewerkingen (**C**reate, **R**ead, **U**pdate, **D**elete). In de onderstaande tabel vind je een overzicht
van deze endpoints.

<table>
    <thead>
        <tr>
            <th></th>
            <th>Method</th>
            <th>URI</th>
            <th>Omschrijving</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>index</th>
            <td><code>GET</code></td>
            <td><code>/api/persons</code></td>
            <td>Geeft een lijst van <code>Person</code> models.</td>
        </tr>
        <tr>
            <th>store</th>
            <td><code>POST</code></td>
            <td><code>/api/persons</code></td>
            <td>Maakt een nieuw <code>Person</code> aan.</td>
        </tr>
        <tr>
            <th>show</th>
            <td><code>GET</code></td>
            <td><code>/api/persons/{person}</code></td>
            <td>Toont een specifieke <code>Person</code>.</td>
        </tr>
        <tr>
            <th>update</th>
            <td><code>PUT</code>/<code>PATCH</code></td>
            <td><code>/api/persons/{person}</code></td>
            <td>De gegevens van een <code>Person</code> bewerken.</td>
        </tr>
        <tr>
            <th>delete</th>
            <td><code>DELETE</code></td>
            <td><code>/api/persons/{person}</code></td>
            <td>Een <code>Person</code> verwijderen.</td>
        </tr>
    </tbody>
</table>

**BELANGRIJK!** De `Person` model maakt gebruik van *soft-deletes*. Dit betekend dat het aanroepen van de **delete**
endpoint wel uit het zicht haalt van de meeste gebruikers, maar niet verwijderd uit de database. Alleen de
database-beheerders kunnen een `Person` volledig verwijderen.