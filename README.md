# cloud_services

**Instrukcja uruchomienia**

1. Git clone
2. composer install
3. W pliku .env zmienić _login_ i _pass_ na dostępy do bazy danych
4. Stworzenie bazy danych

`bin/console doctrine:database:create`

5. Uruchomienie migracji do stworzenia tabel w bazie

`bin/console doctrine:migrations:migrate `

6. Uruchomienie generowania listy produktów, obiektu użytkownika i kluczy API

`bin/console doctrine:fixtures:load` 

i jak zapyta to wprowadzamy yes
   
7. Uruchomienie serwera lokalnego 

`symfony server:start `

   W przeglądarce projekt powinien być dostępny pod adresem localhost:8000
   
Projekt jest podzielony na dwa bundle 
1. BackendBundle który zajmuje się obsługą żądań po API
2. FrontEndBundle który zajmuje się wyświetlaniem strony i wysyłaniem danych do API