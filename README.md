# System Wyników Badań Pacjentów

## Opis projektu
Kompletny system do zarządzania danymi pacjentów oraz ich wynikami badań laboratoryjnych.
Projekt obejmuje:

- import danych z pliku CSV,
- backend API z autoryzacją JWT (Laravel),
- frontend w Vue.js,
- środowisko uruchamiane w Dockerze,
- automatyzację CI/CD (GitLab).

## Uruchomienie lokalne (Docker + Makefile)

W repozytorium znajduje się **Makefile**, który ułatwia uruchomienie całego środowiska.

```bash
make all
```
Polecenie wykonuje:
- budowę kontenerów Docker,
- instalację zależności backendu i frontend,
- migracje bazy danych,
- import danych pacjentów i wyników badań.

<span style="font-size: 20px;">Alternatywna procedura krok po kroku</span>

Jeśli make all nie powiedzie się lub chcesz wykonać kroki ręcznie:

```bash
cd backend
cp .env.example .env
docker-compose build --no-cache
docker-compose up -d

docker exec -it laravel_app composer install
docker exec -it laravel_app php artisan migrate
docker exec -it laravel_app php artisan app:import-patient-data results.csv

docker exec -it vue_app npm install

docker exec -it laravel_app php artisan jwt:secret
```
Po wygenerowaniu sekretu JWT należy wkleić go do pliku .env.

Każdy krok powinien zakończyć się sukcesem przed przejściem dalej.

### Dostępne komendy Makefile:

| Komenda | Opis |
|---------|------|
| `make build` | Budowa kontenerów Docker (backend, frontend, baza danych) |
| `make up` | Uruchamia kontenery w tle |
| `make down` | Zatrzymuje kontenery |
| `make restart` | Restartuje wszystkie kontenery |
| `make logs` | Podgląd logów kontenerów |
| `make bash` | Wejście do kontenera backend (Laravel) |
| `make migrate` | Uruchomienie migracji Laravel |
| `make migrate-refresh` | Odświeżenie migracji Laravel |
| `make seed` | Wypełnienie bazy danych danymi testowymi |
| `make import` | Import danych pacjentów i wyników z `results.csv` |
| `make composer` | Instalacja zależności backendu |
| `make npm` | Instalacja zależności frontend |
| `make db` | Wejście do powłoki PostgreSQL |
| `make php-version` | Sprawdzenie wersji PHP w kontenerze |


### Import danych CSV
Plik: `results.csv`

Format:
patientId	patientName	patientSurname	patientSex	patientBirthDate	orderId	testName	testValue	testReference
### Import danych do bazy:
`make import`

Logi:
- błędy → storage/logs/import_errors.log
- poprawne rekordy → storage/logs/import_success.log

## API
### Logowanie

`POST /api/login`
```bash
Body JSON:

{
  "login": "PiotrKowalski",
  "password": "1983-04-12"
}
```
Zwraca token JWT, który będzie używany do autoryzacji dalszych zapytań.

### Pobieranie wyników

`GET /api/results`

Nagłówek:
`Authorization: Bearer <token>`
```bash
Przykładowa odpowiedź:
{
  "patient": {
    "id": 10,
    "name": "John",
    "surname": "Smith",
    "sex": "m",
    "birthDate": "2021-01-01"
  },
  "orders": [
    {
      "orderId": "20",
      "results": [
        {"name": "foo","value": "1","reference": "1-2"},
        {"name": "bar","value": "2","reference": "1-2"}
      ]
    }
  ]
}
```

### Obsługa błędów
401 – nieautoryzowany dostęp (np. brak tokenu lub nieważny token).
404 – brak danych dla pacjenta.

# Frontend (Vue.js)
## Formularz logowania:
- Login: imię + nazwisko pacjenta
- Hasło: data urodzenia w formacie YYYY-MM-DD
- Po zalogowaniu użytkownik zobaczy dane pacjenta i listę wyników badań.

### Token JWT jest przechowywany w LocalStorage.
Automatyczne wylogowanie po wygaśnięciu tokenu.

## Uruchomienie frontend
```bash
make npm
npm run serve
```
Domyślnie frontend dostępny na: http://localhost:5173/.

## Baza danych
- Obsługuje pacjentów, zamówienia i wyniki badań.
- Baza danych (Postgres):

        Host: localhost

        Port: 5439

        Użytkownik: postgres

        Hasło: postgres

        Baza: medical

- Po uruchomieniu kontenera Laravel migracje można wykonać komendą:
`make migrate`


## CI/CD (GitHab)
Plik konfiguracyjny: .github/workflows/ci.yml

    - Uruchamia testy jednostkowe i integracyjne dla API.
    - Buduje aplikację frontendową
    - Buduje i wypycha obraz Docker

Uruchamia się automatycznie po 
`git hush origin master`

## Uruchomić testy
`docker exec -it laravel_app php artisan test`
