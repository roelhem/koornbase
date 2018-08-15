# Test-omgeving Inleiding

Bij de KoornBase komt een test omgeving gebaseerd op [Laravel Homestead](https://laravel.com/docs/5.6/homestead).
Het systeem is/wordt ontwikkeld met behulp van deze test-omgeving.

Je kunt een test-omgeving van de KoornBase op je eigen systeem installeren om te experimenteren met het systeem
zonder de centrale KoornBase te beinvloeden. Dit is handig bij het ontwikkelen van applicaties die gebruik maken
van de *KoornBase API's*. (Dit wordt **zeer aangeraden** als je een applicatie maakt die gegevens op de KoornBase 
aanpast.)


- [Inleiding](#test-omgeving-inleiding)
- [Requirements](#*requirements*)
- [Installatie](#installatie)
  1. [Vagrant box](#1-installeer-de-*'vagrant-box'*)
  2. [KoornBase Broncode](#2-koornbase-broncode-initialiseren)
  3. [Homestead](#3-homestead-aanmaken/configureren)
     - [hosts](#hosts-instellen)
  4. [Virtual Machine](#4-testomgeving-vm-opstarten/gebruiken)
     1. [Opstarten](#4.1-opstarten)
     2. [SSH](#4.2-server-side-commando's-(ssh))
     3. [Afsluiten/Verwijderen](#4.3-afsluiten/verwijderen)
  5. [Database](#5-database-initialiseren)
     1. [Configureren (migrations)](#5.1-*'migrations'*-uitvoeren)
     2. [Vullen (seeding)](#5.2-database-vullen)
- [Handleiding](#handleiding)
  - [Inloggen](#inloggen)
  
  
  
# *Requirements*

De rest van dit document gaat ervanuit dat je de volgende onderdelen geïnstaleerd hebt op jouw systeem. Kijk voor
meer informatie in de [Officiële Homestead Documentatie](https://laravel.com/docs/5.6/homestead#first-steps).

 - **[GIT-CLI](https://git-scm.com)**
 
   Wordt alleen gebruikt voor het *'clonen'* van deze repository. Je zou ook alle bestanden kunnen downloaden via de
   [GitHub pagina](https://github.com/roelhem/koornbase).

 - **[PHP 7.1.3](http://php.net/manual/en/install.php) of hoger**
   
   Je kunt controleren of dit juist is geinstalleerd via het commando `$ php -version`. Je zou dan iets vergelijkbaars
   als hieronder moeten krijgen.
   
   ```
   PHP 7.1.14 (cli) (built: Feb  7 2018 18:33:30) ( NTS )
   Copyright (c) 1997-2018 The PHP Group
   Zend Engine v3.1.0, Copyright (c) 1998-2018 Zend Technologies

   ```
   
 - **[Composer 1.6.2](https://getcomposer.org) of hoger**
 
   Deze documentatie gaat ervan uit dat je composer globaal geïnstalleerd hebt op je systeem. Alle stappen in dit
   document kunnen ook worden uitgevoerd met een locale of tijdelijke installatie (met een `composer.phar` bestand).
   Pas in dit geval elk comando dat begint met *"$ composer"* aan.
   
   Gebruik het commando `$ composer -version` om te controleren of alles goed geïnstalleerd is. Je zou dan iets
   vergelijkbaars moeten krijgen als:
   
   ```
   Composer version 1.6.2 2018-01-05 15:28:41
   ```
   
 - **[VirtualBox 5.2](https://www.virtualbox.org/wiki/Downloads) of een andere ___virtualizer___**
 
   Aangeraden wordt om [VirtualBox 5.2](https://www.virtualbox.org/wiki/Downloads) te gebruiken. Dit programma is
   *gratis*, ondersteunt de meeste functies en is het makkelijkst om in te stellen.
   
   Je kunt ook [VMWare](https://www.vmware.com/), 
   [Parallels](https://www.parallels.com/products/desktop/) of 
   [Hyper-V](https://docs.microsoft.com/en-us/virtualization/hyper-v-on-windows/quick-start/enable-hyper-v) gebruiken.
   Er is dan wel iets meer configuratie nodig (zie de [Officiële Homestead documentatie](https://laravel.com/docs/5.6/homestead#first-steps)).
   
 - **[Vagrant](https://www.vagrantup.com/downloads.html)**
 
   Gebruik het commando `$ vagrant --version` om te controleren of dit goed geïnstalleerd is. Je zou dan iets
   vergelijkbaars moeten krijgen als:
   
   ```
   Vagrant 2.0.1
   ```
   
# Installatie

In de volgende hoofdstukjes wordt stap voor stap uitgelegd hoe je een *Koornbase-testomgeving* op je eigen systeem
aanmaakt.

## 1 Installeer de *'vagrant box'*

**LET OP!** Dit hoofdstuk gaat ervanuit dat je **Homestead** voor het eerst gebruikt!

Als **Vagrant** en de *Virtualizer* geïnstalleerd zijn, kun je de `laravel/homestead` box installeren. Voer hiervoor
het volgende commando uit:

```
$ vagrant box add laravel/homestead
```

Krijg je hier een foutmelding? Installeer de nieuwste versie van [Vagrant](https://www.vagrantup.com/downloads.html) 
 en probeer het opnieuw.

> Het uitvoeren van dit commando kan een behoorlijk tijdje duren. In deze stap wordt namelijk de software gedownload
> die door de testomgeving gebruikt gaat worden. Aan het begin van de 
> [Homestead documentatie](https://laravel.com/docs/5.6/homestead#introduction) kun je een lijst vinden van de software 
> die **Homestead** gebruikt.

Je kunt nu **Homestead** testomgevingen aanmaken.

## 2 KoornBase broncode initialiseren

In dit hoofdstukje downloaden we de broncode van de KoornBase en zorgen we ervoor dat alle server-side scripts
uitgevoerd kunnen worden.

Ga naar de map waarvanuit je de testomgeving wilt draaien (voor dit voorbeeld `~/workspace`) en clone deze repository
in deze map.

```
$ cd ~/workspace
$ git clone https://github.com/roelhem/koornbase
```

Als je het bovenstaande commando uitvoerd, wordt er een nieuwe map met de naam `koornbase` aangemaakt. Alle
hieropvolgende commando's worden vanuit deze map uitgevoerd.

```
$ cd koornbase
```

We zorgen eerst dat alle *composer dependencies* geïnstalleerd worden zodat de PHP-scripts uitgevoerd kunnen worden.

```
$ composer install
```

> Omdat de **KoornBase** gebouwd is met [Laravel](https://laravel.com/docs/5.6/), worden op het eind van dit commando
> enkele scripts uitgevoerd die **PHP 7.1.3** of hoger vereisen. Controleer dus of **composer** de goede versie van
> **PHP** gebruikt als er in de laatste stap van **composer** iets mis gaat.

De KoornBase-map moet een bestand met de naam `.env` bevatten. In dit bestandje staan enkele instellingen die bepalen
op welke mannier het KoornBase-systeem uitgevoerd moet worden. Om te beginnen kun je de inhoud van het
bestand `.env.example` kopiëeren.

```
$ cp .env.example .env
```

Als laatste stap moeten we een *key* genereren voor de applicatie. Deze *key* wordt onder andere gebruikt om met de 
testomgeving te communiceren.

```
$ php artisan key:generate
```

De broncode is nu klaar om gebruikt te worden op een server.

## 3 Homestead aanmaken/configureren

In dit hoofdstukje maken we de bestanden aan die nodig zijn om de testomgeving op te starten. In deze bestanden
veranderen we een paar instellingen om de virtuele *KoornBase-server* goed te laten werken op jouw systeem.

> De stappen in dit hoofdstukje gaan ervanuit dat je **Homestead** alleen gaat gebruiken voor de KoornBase testomgeving.
> We doen daarom een [Per Project Installation](https://laravel.com/docs/5.6/homestead#per-project-installation) van
> **Homestead**.
>
> Als je **Homestead** ook voor andere toepassingen wilt gebruiken, kun je 
> [hier](https://laravel.com/docs/5.6/homestead#first-steps) vinden hoe je één (globale) **Homestead** omgeving voor al 
> je toepassingen kunt aanmaken.

Voer het volgende commando uit om de bestanden aan te maken die nodig zijn om **Homestead** te gebruiken:

```
$ php vendor/bin/homestead make
```

> Voor *Windows*, gebruik `vendor\\bin\\homestead make`.

Dit commando maakt onder andere het bestand **Homestead.yaml** aan. In dit bestand staan de instellingen van de 
*virtuele server*. De inhoud van dit bestand zou vergelijkbaar moeten zijn de onderstaande code. De comments in
de onderstaande code geven aan hoe je het bestand moet aanpassen om **Homestead** goed te laten werken voor de
**KoornBase**.

```yaml
ip: 192.168.10.10 # Kies hier een IP-adres dat niet in je locale netwerk gebruikt wordt.
memory: 2048
cpus: 1
provider: virtualbox # Pas deze waarde aan als je een andere 'virtualizer' gebruikt
authorize: ~/.ssh/id_rsa.pub
keys:
    - ~/.ssh/id_rsa
folders:
    -
        map: /Users/roel/workspace/koornbase # De map waar de KoornBase-broncode staat.
        to: /home/vagrant/code
sites:
    -
        map: homestead.test # Verander deze waarde naar 'koornbase.test'
        to: /home/vagrant/code/public
databases:
    - homestead
name: koornbase
hostname: koornbase
```

Nadat je deze wijzigingen hebt opgeslagen, is **Homestead** klaar om opgestart te worden.

### Hosts instellen

De domeinnaam *koornbase.test* werkt niet automatisch op je systeem. Hoewel het niet strict nodig is om deze in te
stellen, zorgt het er wel voor dat je soepeler met de *url's* kunt werken. Dit zorgt er namelijk voor dat je de
website van het KoornBase-systeem in de testomgeving kunt bereiken via de url `https://koornbase.test/`.

Als we dit willen gebruiken, moeten het `hosts` bestand van je systeem worden aangepast. Op Mac en Linux is de locatie 
van dit bestand meestal `/etc/hosts`, op Windows is de locatie meestal `C:\Windows\System32\drivers\etc\hosts`.
Voeg aan dit bestand de volgende regels toe:

```
192.168.10.10 homestead.test
192.168.10.10 koornbase.test
``` 

> De eerste regel is optioneel, maar wel handig. Laat deze regel weg als je de domeinnaam
> *homestead.test* niet wilt gebruiken voor de **KoornBase**. Het *IP-adres* moet gelijk zijn aan het *IP-adres*
> in de *Homestead.yaml* bestand.

> Werken de domeinnamen niet, ookal heb je het `hosts` bestand aangepast? Herstarten van je computer helpt meestal.


## 4 Testomgeving VM opstarten/gebruiken

In dit hoofdstukje starten we de *Virtual Machine* van de testomgeving op. Daarnaast behandelen we een paar 
basis commando's om de testomgeving te gebruiken.

Voer het volgende commando uit om de testomgeving op te starten:

### 4.1 Opstarten

```
$ vagrant up
```

> Als de testomgeving is opgestart, zou de homepage van de KoornBase bereikbaar moeten zijn. Ga naar 
> [https://koornbase.test/](), of [https://127.0.0.1:43300/]() om de homepage te openen.

### 4.2 Server-side commando's (SSH)

Alle command-line opdrachten voor de KoornBase moeten uitgevoerd worden op de *VM* van de testomgeving. Met het
volgende commando start je gemakkelijk een **ssh**-connectie:

```
$ vagrant ssh
```

> In het vervolg van dit document voegen we steeds `vagrant@koornbase ~$` toe om aan te geven dat het commando
> in een bepaalde map op de *VM* moet worden uitgevoerd. Commando's die beginnen met `$` daarintegen moeten je op je
> eigen systeem uitvoeren (meestal in de map met de broncode.)

De broncode van de KoornBase staat in de map `~/code` op de *VM*. De meeste commando's (met name die beginnen met 
`php artisan`) moeten in deze map worden uitgevoerd. Ga naar deze map met het volgende commando:

```
vagrant@koornbase ~$ cd ~/code
```

Om de **ssh**-connectie te sluiten kun je het commando `logout` gebruiken.

### 4.3 Afsluiten/verwijderen

Met het volgende commando wordt de testomgeving (tijdelijk) afgesloten:

```
$ vagrant halt
```

Dit zorgt ervoor dat de virtual machine afgesloten wordt en alle gegevens bewaart blijven. Op deze mannier blijven
bijvoorbeeld de gegevens in de database bewaart.


Als je de testomgeving volledig wilt afsluiten (en verwijderen), gebruik je het volgende commando:
```
$ vagrant destroy --force
```

## 5 Database Initialiseren

In dit hoofdstukje zorgen we ervoor dat de structuur van de database in de testomgeving wordt ingesteld. Daarnaast
vullen we de database met willekeurige gegevens die het makkelijk maken om te beginnen.

### 5.1 *'Migrations'* uitvoeren

Eerst moeten we de database dezelfde structuur geven als die van de *KoornBase-database*. Dit doen we door het volgende
commando uit te voeren in de `~/code` map in de *VM*:

```
vagrant@koornbase ~/code$ php artisan migrate
```

Dit commando moet altijd worden uitgevoerd nadat je een nieuwe versie van de broncode hebt gedownload. Voor meer
informatie over *migrations*, zie de [Laravel Docs over Migrations](https://laravel.com/docs/5.6/migrations).

### 5.2 Database vullen

Met het volgende commando wordt de database gevuld met (willekeurig gegenereerde) gegevens. Dit zorgt ervoor dat je
direct kunt beginnen met het systeem in de testomgeving.

```
vagrant@koornbase ~/code$ php artisan db:seed
```

Je kunt ook de database structuur aanmaken èn de database vullen in één commando: 

```
vagrant@koornbase ~/code$ php artisan migrate:fresh --seed
```

Voor meer informatie over *seeding*, zie de [Laravel Docs over Seeding](https://laravel.com/docs/5.6/seeding).

# Handleiding

Nadat je alle stappen van de [installatie](#installatie) hebt uitgevoerd zoals hierboven beschreven, kun je
het *KoornBase-systeem* in de testomgeving gebruiken. De homepage kun je vinden op 
[https://koornbase.test/]() (òf [https://127.0.0.1:43300/]() ).

Het *KoornBase-systeem* in de testomgeving werkt bijna hetzelfde als de normale *KoornBase-systeem*. 
We behandelen daarom in de volgende hoofstukjes alleen de onderdelen die iets anders
werken.

## Inloggen

Inloggen op het KoornBase-systeem in de testserver kan via de pagina's [https://koornbase.test/login]() 
of [https://127.0.0.1:43300/login]().

### Test Gebruikers
Als je [de database hebt gevuld](#database-vullen) met (willekeurig) gegenereerde gegevens, zijn er ook enkele 
(willekeurige) gebruikers aan aangemaakt. Naast de 'normale', willekeurige gebruikers, zijn er de volgende 'speciale' 
gebruikers:

<table>
    <thead>
        <tr>
            <th>Naam</th>
            <th>E-mail</th>
            <th>Rechten</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>admin</td>
            <td>admin@koornbase.test</td>
            <td>Admin</td>
        </tr>
        <tr>
            <td>super</td>
            <td>super@koornbase.test</td>
            <td><em>alle</em></td>
        </tr>
    </tbody>
</table>

 Alle gebruikers die automatisch zijn gegenereerd, hebben als wachtwoord `secret`.
