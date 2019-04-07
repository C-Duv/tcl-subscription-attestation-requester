Script pour demander une Attestation d'abonnement TCL
=====================================================

Ce script permet de demander aux [TCL](https://tcl.fr) une a Attestation
d'abonnement.

Configuration
-------------

Modifier le tableau `subscriptions` retourn� par le fichier de configuration
`config.php` pour y saisir les informations suivantes :
* Numero de carte T�c�ly (`subscription_number`)
* Date de naissance (`subscriber_birth_date`)
* Adresse e-mail (`send_certificate_to_email`)

Exemple :

```Php
// [...]
    'subscriptions' => [
        'John Doe' => [
            'subscription_number' => '012345678912',
            'subscriber_birth_date' => '1970-01-01',
            'send_certificate_to_email' => 'john@example.com',
        ],
        'Jane Doe' => [
            'subscription_number' => '219876543210',
            'subscriber_birth_date' => '1970-01-01',
            'send_certificate_to_email' => 'jane@example.com',
        ],
    ],
// [...]
```

Ex�cution
---------

```Shell
php demande.php
```

Fonctionnement
--------------

Chaque �l�ment du tableau `subscriptions` du fichier de configuration correspond
� un abonnement pour lequel il faut r�cup�rer une attestation.

Le script utilise les informations du tableau pour g�n�rer la/les requ�te(s)
HTTP correspondante(s) � soumettre au serveur HTTP des TCL (cf. le [Formulaire
de demande d'attestation de paiement](https://tcl.fr/Mon-TCL/Service-Attestation-d-abonnement2)).
La demande d'attestation g�n�r�e porte sur le mois courant (celui de l'ex�cution
du script).

Une v�rification simpliste du contenu HTML retourn� permet d'indiquer la bonne
prise en compte de la demande.
