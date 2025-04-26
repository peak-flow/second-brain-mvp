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

## Linting/Formatting
- `./vendor/bin/pint` - Run Laravel Pint code formatter

## Code Style
- PHP: PSR-12 standard, type hints required
- Classes: PascalCase (`TaskPlanner`)
- Methods/Variables: camelCase (`parseSteps()`, `$taskDescription`)
- Blade Templates: kebab-case for components
- Use Laravel conventions for controllers, models, and services
- Livewire component naming must match file/class name
- Follow existing error handling patterns using exceptions and validation
- when creating features make them as simple as possible
- when saving commits do not mention created by claude api
- when adding new features commit the changes using conventional commits

## Information
- README-architecture.md has list of all the features of app, when adding new features if not in list add it
- README-todo.md has list of future features to add, when completing feature update this file