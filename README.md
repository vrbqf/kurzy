# Správa kurzů a přihlášek (Laravel)

Školní projekt postavený na frameworku **Laravel**, který slouží pro správu kurzů, evidenci účastníků a kontrolu kapacity přihlášek.

## Hlavní funkce
- **Kurzy & Účastníci:** Evidence kurzů (název, kód, kapacita, datum začátku) a účastníků.
- **Business logika & Validace:**
    - Prevence duplicitních přihlášek.
    - Sledování kapacity kurzu (aktivní přihlášky `enrolled` a `completed`, zrušené `cancelled` uvolňují místo).
    - Unikátní kódy kurzů a emaily účastníků.
- **Automatické testy:** Implementované Feature testy pro ověření správného ukládání a fungování aplikace.

## Spuštění projektu
1. Klonujte repozitář:
   ```bash
    git clone [https://github.com/vrbqf/kurzy.git](https://github.com/vrbqf/kurzy.git)
   
2. Nainstalujte závislosti:
    ```bash
    composer install
   
3. Zkopírujte konfigurační soubor a vygenerujte klíč:
    ```bash
    cp .env.example .env
    php artisan key:generate 
   
4. Spusťtě migraci a lokální server:
   ```bash
    php artisan migrate
    php artisan serve
