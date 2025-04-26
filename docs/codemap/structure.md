# Second Brain MVP - Codebase Structure

This document provides a high-level overview of the codebase structure and architecture to help developers understand how the components fit together.

## Application Structure

```
second-brain-mvp/
├── app/                 # Application code
│   ├── Http/            # HTTP layer (controllers, middleware)
│   ├── Livewire/        # Livewire components
│   ├── Models/          # Eloquent models
│   ├── Providers/       # Service providers
│   └── Services/        # Domain services
├── config/              # Configuration files
├── database/            # Database migrations and seeders
├── resources/           # Frontend resources
│   ├── css/
│   ├── js/
│   └── views/           # Blade templates
├── routes/              # Route definitions
└── tests/               # Test suite
```

## Key Components

The application is built using Laravel with Livewire for reactive components. The main features include:

1. **Tree Management**: Hierarchical organization of content
2. **Task Planning**: AI-assisted task breakdown and management
3. **Agent Creation**: Custom AI agent definition with prompts
4. **Project Management**: Organization of trees and tasks

## Domain Model

The core entities in the system are:

- **User**: Authenticated user of the system
- **Project**: Container for related trees and tasks
- **Tree**: Hierarchical node within a project
- **Task**: Actionable item with steps
- **Topic**: Subject area that can be associated with trees
- **AgentPrompt**: Custom prompts for AI agents

## Flow Diagrams

### Task Planning Flow

```
User -> TaskPlanner Component -> TaskPlannerService -> OpenAI API -> Parsed Steps -> Task Model
```

### Tree Management Flow

```
User -> TreeManager Component -> Tree Model -> Database
```