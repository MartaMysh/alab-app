# System Wyników Badań Pacjentów

## Opis projektu
Projekt umożliwia:
- Import danych pacjentów i wyników badań z pliku CSV (`results.csv`).
- Udostępnianie wyników poprzez API z autoryzacją JWT.
- Wyświetlanie danych pacjenta i wyników badań na frontendzie Vue.js.
- Lokalną pracę z Dockerem i automatyzację CI/CD (GitLab).

---

## Uruchomienie lokalne (Docker + Makefile)

W repozytorium znajduje się **Makefile**, który ułatwia uruchomienie całego środowiska.

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

### Przykładowe uruchomienie projektu
```bash
make all
```
make all wykona kolejno: budowę kontenerów, uruchomienie środowiska, odświeżenie migracji i import CSV.

### Import danych CSV
Plik: `results.csv`

Format:
patientId	patientName	patientSurname	patientSex	patientBirthDate	orderId	testName	testValue	testReference
### Import danych do bazy:
`make import`

Logi błędów i poprawnie zaimportowanych rekordów zapisane w storage/logs/import.log.
## API
### Logowanie

`POST /api/login`

Body JSON:

{
  "login": "PiotrKowalski",
  "password": "1983-04-12"
}
Zwraca token JWT, który będzie używany do autoryzacji dalszych zapytań.
Pobieranie wyników

`GET /api/results`

Nagłówek:
Authorization: Bearer <token>
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
- Baza danych PostgreSQL
- Po uruchomieniu kontenera Laravel migracje można wykonać komendą:
`make migrate`

## CI/CD (GitLab)
Plik konfiguracyjny: .gitlab-ci.yml

## Uruchomić testy

`docker exec -it laravel_app php artisan test`
