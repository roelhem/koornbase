#  JSON-Resource Parameters

Bij de meeste functies van de REST-API wordt er een json-object terug gegeven die gegevens van de database
representeert. ( Deze JSON-resources zijn the herkennen aan dat ze vaak een `id` key hebben ). De meeste
JSON-resources hebben een paar gedeelde instellingen die kunnen worden aangeroepen via de URL-parameters van de 
meeste requests.

Hieronder staan de verschillende URL-parameters die gebruikt kunnen worden om de meeste JSON-resources
in te stellen.

## Overzicht

<table>
    <thead>
        <tr>
            <th>URL-Parameter</th>
            <th>Verwachte waarden</th>
            <th>Omschrijving</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>metaFields</code></td>
            <td><tt>string[]</tt> | <tt>comma-gescheiden lijst</tt></td>
            <td>Geeft aan welke meta-velden getoond moeten worden.</td>
        </tr>
        <tr>
            <td><code>metaFieldsGrouped</code></td>
            <td><tt>leeg</tt></td>
            <td>Geeft aan hoe de metaFields getoond moeten worden.</td>
        </tr>
    </tbody>
</table>

## Uitgebreide uitleg

### Parameter `metaFields` .

Deze parameter geeft aan welke meta-velden de JSON-Resources moeten tonen. De waarde kan een array met meta-veld namen
zijn, of een lijst meta-veld namend die door comma's zijn gescheiden. Het voorbeeld hieronder toont de verschillende
mannieren waarop deze parameter gebruikt kan worden:

```
https://path/to/endpoint?metaFields=_className

https://path/to/endpoint?metaFields=_className,_tableName,_singularName

https://path/to/endpoint?metaFields[]=_className&metaFields[]=_tableName&metaFields[]=_singularName
```

De bovenste twee mannieren zijn veel duidelijker om te lezen, maar de onderste mannier voorkomt fouten in het uitlezen
en versturen van de *request*. Gebruik de bovenste twee mannieren om snel dingen uit te testen of als je geen 
*URL query builder* hebt in de omgeving waarmee je gebruik maakt van de REST-API. Gebruik in alle andere gevallen
altijd de onderste mannier. (Zeker als je een *URL query builder* gebruikt is dit veel makkelijker.)

#### Mogelijk waarden (meta-veld namen)

- `_className`

  Toont de naam van de **PHP Class** die de *JSON-resource* representeerd. Is handig voor
  de ontwikkelaars van centrale database of ontwikkelaars die graag willen helpen met de centrale database. De
  belangrijkste taak van dit meta-veld is om beter te begrijpen hoe het systeem in elkaar zit.
  
- **Meta-velden die informatie geven over hoe de data is opgeslagen in de database**
    - `_tableName`  
    
      *(Alleen voor JSON-resources die database-models representeerd)*. Toont de naam van
      de belangrijkste tabel in de database die wordt gebruikt om de gegevens in de JSON-resource te tonen.
      
      De waarde van `_tableName` in combinatie met de waarde van `id` kan bijna elk onderdeel van de database uniek
      bepaald worden. Deze waarde is dus zeer geschikt om op te slaan in je eigen applicatie zodat je later weer
      gemakkelijk naar dezelfde gegevens kunt verwijzen.
      
    - `_singularName`
    
      *(Alleen voor JSON-resources die database-models representeerd)*. Geeft dezelfde waarde als `_tableName`, alleen 
      dan in enkelvoud geschreven. De naam van `_tableName` is namelijk altijd een engelse naam in meervoud die omschrijft 
      wat er in de tabel wordt opgeslagen. 
      
      Je kunt de waarde van `_singularName` op dezelfde mannier gebruiken als `_tableName`. Dit is vooral nuttig als het 
      logischer is voor jouw applicatie dat de namen enkelvoudig zijn.
      
    - `_primaryKeyName`
    
      *(Alleen voor JSON-resources die database-models representeerd)*. Geeft de naam van de attribute die de waarde van
      de *primary key* in de database-tabel bevat. De waarde van dit veld zal in bijna alle gevallen `id` zijn. Kan
      gebruikt worden voor *high-level REST-API clients* die soms om deze waarde vragen.
      
    - `_primaryKeyType`
    
      *(Alleen voor JSON-resources die database-models representeerd)*. Geeft de data-type van de primary key. Dit is
      in de meeste gevallen een integer (geeft de waarde `int`). Er zijn wel een paar tabellen in de database die een
      string (geeft de waarde `string`) gebruiken als primary key (Bijvoorbeeld in het RBAC-gedeelte).
      
      Dit veld kan erg handig zijn om deze paar uitzonderingen gemakkelijker te detecteren. Ook kan dit veld handig
      zijn als je een *high-level REST-API client* gebruikt.
      
    - `_primaryKey`
      
      Dit is een snelkoppeling om zowel `_primaryKeyName` als `_primaryKeyType` te tonen.

- **Meta-velden die informatie geven over de geschiedenis van de getoonde data**
  
    - `_created_at`
    
      Geeft de datum waarop de data die wordt getoond is aangemaakt.
      
    - `_created_by`
    
      Geeft de `id` van de gebruiker die de data heeft aangemaakt.
      
    - `_created`
    
      Een snelkoppeling om zowel `_created_at` als `_created_by` te tonen.
        
    - `_updated_at`
    
      Geeft de datum waarop de data het laatst is bewerkt.
      
    - `_updated_by`
    
      Geeft de `id` van de gebruiker die het laatst dingen heeft geweizigd.
      
    - `_updated`
    
      Een snelkoppeling om zowel `_updated_at` als `_updated_by` te tonen.
 
 
 
 
 
 ### Parameter `metaFieldsGrouped` .
 
 Deze parameter geeft aan hoe de meta-velden getoond moeten worden. Als deze parameter niet wordt gebruikt worden
 alle velden direct in de JSON-resource getoond (als zusters van de andere data). Als deze parameter wel wordt
 toegevoegd, worden alle meta-velden gegroepeerd in een `_meta` attribute.
 
 Het is voldoende om de parameter alleen maar toe te voegen aan de url-parameters zoals in het volgende voorbeeld:
 
 ```
https://path/to/endpoint?metaFields=_created,_updated&metaFieldsGrouped
```
 
#### Voorbeeld

Als antwoord op een bepaalde API-request geeft de server het volgende JSON-object:
 
 ```json
{
  "data": {
    "id":5,
    "name":"Voorbeeld",
    "description":"Alleen bedoeld om uit te leggen hoe de metaFieldsGrouped parameter werkt."
  }
}
```

Toevoegen van de `metaFields` parameter aan de request zal het resultaat als volgt veranderen. (We vragen in de
request om `_primaryKey` te tonen):

 ```json
{
  "data": {
    "id":5,
    "name":"Voorbeeld",
    "description":"Alleen bedoeld om uit te leggen hoe de metaFieldsGrouped parameter werkt.",
    "_primaryKeyName":"id",
    "_primaryKeyType":"int"
  }
}
```

Zoals je ziet worden de velden toegevoegd alsof het normale velden zijn. Het enige verschil tussen de normale
velden is dat de meta-velden met een `_` beginnen. Het kan vervelend zijn om dit op elke plek te moeten controleren
als je een of meerdere meta-velden nodig hebt in je applicatie.

Hieronder staat het resultaat van de server alleen de parameter `metaFieldsGrouped` wordt toegevoegd aan de request.

 ```json
{
  "data": {
    "id":5,
    "name":"Voorbeeld",
    "description":"Alleen bedoeld om uit te leggen hoe de metaFieldsGrouped parameter werkt.",
    "_meta": {
        "_primaryKeyName":"id",
        "_primaryKeyType":"int"
    }
  }
}
```

Zoals je ziet worden alle meta-velden in een apart object gestopt met als key `_meta`. Je kunt nu zo veel
meta-velden opvragen zonder dat het lastiger wordt om de meta-velden te onderscheiden van de normale velden.

Als je `metaFieldsGrouped` toevoegd aan de request wordt het veld `_meta` altijd getoond, bevat altijd een object
en heeft altijd dezelfde naam.