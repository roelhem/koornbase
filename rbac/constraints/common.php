<?php


Rbac::constraint(
    'created',
    'Alleen zelf aangemaakt.',
    'Geeft alleen de modellen die door de huidige gebruiker zijn aangemaakt.'
);

Rbac::constraint(
    'owned',
    'Alleen eigen gegevens.',
    'Geeft alleen de modellen door die gegevens bevatten van de huidige gebruiker. Denk hierbij aan de
    persoon die aan de gebruiker gekoppeld is, de contactgegevens van deze persoon etc.'
);

Rbac::constraint(
    'no_owner',
    'Alle gegevens die niet met accounts zijn gekoppeld.'
);