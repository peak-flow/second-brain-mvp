# Code Map Documentation

This directory contains structured documentation of the codebase, designed to help developers understand the system without having to read all of the code.

## Overview

The Second Brain MVP is a Laravel application that provides hierarchical organization of content with AI-assisted task planning capabilities. The documentation is organized as follows:

## Files

- `structure.md` - High-level overview of the application architecture and data flow
- `models.json` - Detailed documentation of all data models and their relationships
- `components/livewire.json` - Documentation of Livewire components
- `services.json` - Documentation of application services
- `api.json` - Documentation of API endpoints

## How to Use

When working with this codebase:

1. Start with `structure.md` to understand the general architecture
2. Refer to `models.json` to understand the domain model and data relationships
3. Check component documentation when working with UI features
4. Use the API documentation when building new integrations

## Keeping Documentation Updated

When you make changes to the code:

1. Update the relevant JSON file to reflect your changes
2. Keep the structure consistent with the existing format
3. Ensure the documentation accurately represents the current state of the code

## Tools for Working with This Documentation

You can visualize this data using standard JSON tools or by building simple viewers:

```bash
# View a specific file
cat docs/codemap/models.json | jq .

# Generate a visualization (example with a hypothetical tool)
visualize-codemap docs/codemap
```