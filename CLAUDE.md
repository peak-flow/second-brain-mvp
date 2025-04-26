# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Build Commands
- `composer install` - Install PHP dependencies
- `npm install` - Install JS dependencies
- `npm run dev` - Start Vite dev server
- `npm run build` - Build frontend assets
- `php artisan serve` - Start Laravel development server

## Testing
- `./vendor/bin/pest` - Run all tests
- `./vendor/bin/pest tests/path/to/TestFile.php` - Run specific test file
- `./vendor/bin/pest --filter "test description"` - Run specific test by name
- Always create tests for new features with naming convention `FeatureNameTest.php`
- Use TDD when creating new features - write tests first, then implement

## Linting/Formatting
- `./vendor/bin/pint` - Run Laravel Pint code formatter
- Run formatting before committing changes

## Code Style
- PHP: PSR-12 standard, type hints required
- Classes: PascalCase (`TaskPlanner`)
- Methods/Variables: camelCase (`parseSteps()`, `$taskDescription`)
- Blade Templates: kebab-case for components
- Use Laravel conventions for controllers, models, and services
- Livewire component naming must match file/class name
- Follow existing error handling patterns using exceptions and validation
- Keep features as simple as possible
- Use conventional commits for all changes (feat, fix, docs, refactor, etc.)
- Ensure proper validation for all user inputs
- Add proper PHPDoc comments to methods and classes

## Documentation
- Update README-architecture.md when adding new features
- Update README-todo.md when completing features from the list
- Update the Code Map documentation in `/docs/codemap/` when:
  - Adding new models (update `models.json`)
  - Adding new Livewire components (update `components/livewire.json`)
  - Adding new API endpoints (update `api.json`)
  - Adding new services (update `services.json`)
  - Adding new major features (update `index.json`)
- Follow the existing JSON structure format when updating docs
- Keep documentation in sync with implementation
- The Code Map is critical for understanding the codebase structure

## Database
- Always use migrations for database changes
- Update model validation rules when changing database schema
- Use proper data types in migrations (e.g., use proper column types)
- Set appropriate indexes for fields that will be frequently queried