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
│       └── AI/          # AI service layer
│           ├── Contracts/  # AI provider interfaces
│           └── Providers/  # AI provider implementations
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
3. **Agent Creation**: Custom AI agent definition with multi-provider support
4. **Project Management**: Organization of trees and tasks

## Domain Model

The core entities in the system are:

- **User**: Authenticated user of the system
- **Project**: Container for related trees and tasks
- **Tree**: Hierarchical node within a project
- **Task**: Actionable item with steps
- **Topic**: Subject area that can be associated with trees
- **AgentPrompt**: Custom agents with provider, model, and prompt configurations

## AI System Architecture

The application implements a flexible multi-provider AI system:

```
┌─────────────────┐     ┌───────────────┐     ┌─────────────────┐
│ App Components  │     │  AI Manager   │     │ Agent Database  │
│ (TaskPlanner,   │────▶│ (Provider &   │◀───▶│ (AgentPrompt    │
│  AgentCreator)  │     │  Agent Mgmt)  │     │  model)         │
└─────────────────┘     └───────────────┘     └─────────────────┘
                              │   ▲
                  ┌───────────┘   └───────────┐
                  ▼                           ▼
       ┌──────────────────┐           ┌──────────────────┐
       │  OpenAI Provider │           │Anthropic Provider│
       │  Implementation  │           │  Implementation  │
       └──────────────────┘           └──────────────────┘
```

## Flow Diagrams

### Task Planning Flow

```
User -> TaskPlanner Component -> TaskPlannerService -> AIManager -> Provider -> Parsed Steps -> Task Model
```

### Agent Configuration Flow

```
User -> AgentCreator Component -> AgentPrompt Model -> AIManager -> Provider Selection 
```

### Tree Management Flow

```
User -> TreeManager Component -> Tree Model -> Database
```