Yii 2 gebaseerd barkassa systeem.
============================

Deze aplicatie is gemaakt voor het beheren van de bar voor kleine verenigingen.

Installatie:

clone met git
maak de db.php en de email.php files aan in de config folder

Vanaf de root
`composer update`
`php yii migrate/up --migrationPath=@yii/rbac/migrations`
`php yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations`
`php yii migrate/up`

Voeg als eerste een gebruiker toe met de username `beheerder`.
Deze gebruikerkan gerbuikers toevoegen en rechten zetten op andere gebruikers.