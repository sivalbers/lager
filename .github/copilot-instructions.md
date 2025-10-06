# Copilot Instructions for AI Agents

## Project Overview
This is a Laravel-based web application for warehouse management ("lager"). The codebase uses Laravel conventions but includes custom logic for inventory, labels, and data import/export. Key technologies: PHP, Laravel, Livewire, Tailwind CSS, Vite, and SQLite.

## Architecture & Key Components
- **Domain Logic**: Located in `app/`.
  - **Models**: `app/Models/` (e.g., `Artikel.php`, `Lagerdaten.php`, `Lagerort.php`).
  - **Repositories**: `app/Repositories/` for data access abstraction.
  - **Services**: `app/Services/` for business logic (e.g., `ArtikelImportService.php`, `BuchungsService.php`).
  - **Livewire Components**: `app/Livewire/` for interactive UI (e.g., `EtikettenErstellen.php`, `ScanArtikel.php`).
  - **Controllers**: `app/Http/Controllers/` for HTTP endpoints.
- **Views**: Blade templates in `resources/views/` (Livewire views in `resources/views/livewire/`).
- **Routes**: Defined in `routes/web.php` (web), `routes/auth.php` (auth), `routes/console.php` (CLI).
- **Database**: SQLite migrations in `database/migrations/`.

## Developer Workflows
- **Install dependencies**: `composer install` (PHP), `npm install` (JS/CSS)
- **Run dev server**: `php artisan serve` (backend), `npm run dev` (frontend)
- **Build frontend**: `npm run build`
- **Run tests**: `php artisan test` or `vendor/bin/phpunit`
- **Migrate DB**: `php artisan migrate`
- **Seed DB**: `php artisan db:seed`

## Project-Specific Patterns
- **Repository Pattern**: All data access goes through repository classes in `app/Repositories/`.
- **Service Layer**: Business logic is separated into services in `app/Services/`.
- **Livewire**: UI interactivity is handled via Livewire components, with corresponding Blade views.
- **Custom Requests**: Form validation via custom request classes in `app/Http/Requests/`.
- **Tailwind CSS**: Used for styling, configured in `tailwind.config.js`.
- **Vite**: Asset bundling via `vite.config.js`.

## Integration Points
- **External Imports**: Data import logic in `app/Services/ArtikelImportService.php`.
- **OData**: Integration for reading inventory in `app/Services/ODataReadBestand.php`.
- **Authentication**: Configured in `config/auth.php`, routes in `routes/auth.php`.

## Conventions & Tips
- **Use Eloquent ORM for models**; avoid raw SQL unless necessary.
- **Follow Laravel folder structure for new features.**
- **Blade views**: Use `@livewire` for Livewire components.
- **Testing**: Place feature/unit tests in `tests/Feature/` and `tests/Unit/`.
- **Environment**: Use `.env` for config; SQLite DB at `database/database.sqlite`.

## Example: Adding a New Inventory Feature
1. Create a model in `app/Models/`.
2. Add a migration in `database/migrations/`.
3. Add a repository in `app/Repositories/`.
4. Add business logic in `app/Services/`.
5. Create a Livewire component in `app/Livewire/` and Blade view in `resources/views/livewire/`.
6. Register routes in `routes/web.php`.

## Key Files & Directories
- `app/Models/`, `app/Repositories/`, `app/Services/`, `app/Livewire/`
- `resources/views/livewire/`
- `routes/web.php`, `routes/auth.php`
- `database/migrations/`
- `config/`

---
If any section is unclear or missing, please provide feedback for further refinement.