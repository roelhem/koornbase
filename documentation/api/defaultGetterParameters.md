# Standaard ophaal parameters

De meeste antwoorden op requests naar de REST-API tonen uiteindelijk data uit de centrale database. Om ervoor te zorgen
dat de REST-API zo snel mogelijk werkt wordt standaard alleen de belangrijkste en makkelijk opzoekbare data getoond.

Je kunt bij bijna alle requests extra parameters meesturen met de request ( met behulp van URL parameters ). Deze
parameters zorgen ervoor dat naast de standaard gegevens ook andere gegevens worden getoond. Bijna alle functies
in de API hebben hun eigen, specifieke parameters, maar er zijn ook enkele parameters die op het grootste gedeelde
van de functies ook werken.

## De juiste mannier van gebruiken

Deze API is zo opgezet dat het heel makkelijk is om veel gegevens op te vragen met weinig moeite. Dit is voornamelijk
om de leden van de Koornbeurs zo veel mogelijk te motiveren om nieuwe toepassingen met de API te maken. Hoewel het zeer 
aantrekkelijk lijkt om bij elke request zo veel mogelijk gegevens op te vragen, is dit niet de juiste mannier om met 
de API om te gaan.

Ten eerste zorgt het opvragen van veel gegevens ervoor dat het verwerken van requests trager wordt. Hoewel het sneller
is om meer gegevens in een request op te vragen, moet je zo veel mogelijk vermijden dat je gegevens opvraagt die je
uiteindelijk niet gebruikt in je toepassing.

Daarnaast (en misschien wel belangrijker) is het niet aan te raden om veel gegevens op te vragen vanwege het
authenticatiesysteem. Als je een specifieke request doet naar de gevraagde gegevens en het blijkt dat je geen rechten
hebt om deze gegevens in te zien, dan krijg je een foutmelding. 

Dit is anders bij gegevens die je ophaalt met behulp van de methodes die op deze pagina zijn beschreven. In veel
gevallen dat de gebruiker geen rechten heeft om bepaalde gegevens in te zien, worden de gegevens gewoon niet
geladen. De gebruiker kan dus geen onderscheid maken tussen gegevens die niet bestaan en gegevens die hij niet
mag bekijken.

Als je een applicatie bouwt met als doel dat veel mensen er gebruik van gaan maken moet je hier extra op letten. Als
namelijk iemand met weinig rechten je applicatie gebruik, kunnen lastig detecteerbare fouten ontstaan.

## Parameter overzicht

In de onderstaande tabel staan de belangrijkste parameters die op het grootste gedeelte van de API werken. Bekijk de
documentatie van de specifieke functies voor de uitzonderingen op deze regels.

<table>
    <thead>
        <tr>
            <th>Parameter naam</th>
            <th>Verwachte waarde</th>
            <th>Omschrijving</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>with</code></td>
            <td><tt>string[]</tt> | <tt>comma-gescheiden lijst</tt></td>
            <td>Vraagt of de gerealteerde gegevens van relaties ook worden geladen.</td>
        </tr>
    </tbody>
</table>

## Uitgebreide uitleg

### Parameter `with`.

Met deze relatie kun je naast de standaard gegevens ook gerelateerde gegevens van relaties opvragen. *Relaties*
zijn specifieke relaties tussen verschillende objecten in de database. Bij de documentatie van de verschillende 
database-modellen kun vinden welke relaties gedefinieerd zijn voor dat specifieke model.

Je kunt een relatie opvragen door de naam van de relatie als waarde van de `with` parameter mee te geven. Als
je meer dan één relatie wilt opvragen, kun je de verschillende relaties scheiden met een comma (`,`) of als een 
*'array'* versturen.

Hieronder is een voorbeeld van URL-parameters die laten zien hoe je relaties kan opvragen.

```
?with=relatieNaam
?with=relatieA,relatieB
?with[]=relatieA&with[]=relatieB
```

Naast meerdere relaties opvragen van een model kun je ook relaties van relaties opvragen. Je doet dit door de
namen van de relaties te scheiden met een punt (`.`). In dit geval wordt zowel de eigen relaties als de relaties
van relaties geladen. Je kunt zoveel relaties aanvragen als nodig is.

#### Voorbeeld

Stel de database heeft modellen `ModelA`, `ModelB` en `ModelC`. Verder heeft `ModelA` een relatie
naar `ModelB` met de naam `AtoB` en heeft `ModelB` een relatie met `ModelC` met de naam `BtoC`. Met behulp van
de punt-notatie kun je vanuit `ModelA` gerelateerde gegevens van `ModelC` laden.

```
https://endpoint/of/model-a?with=AtoB.BtoC
```

In het resultaat worden nu alle `ModelA`-gegevens van de endpoint gegeven. Daarnaast worden alle `ModelB`-gegevens
die in relatie staan met `ModelA` gegeven en alle `ModelC` gegevens die in relatie staan met deze `ModelB`'s.