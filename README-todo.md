# TODO

## Phase 1: Foundation
- [x] Projects API endpoints and Feature tests
- [x] Tree API endpoints and Feature tests
- [x] Tasks API endpoints and tests
- [x] Topics API endpoints and tests
- [x] Tree manipulation: move and delete nodes
- [x] Endpoint to fetch subtree or depth-limited view
- [x] Livewire TreeManager UI for node management
- [x] Livewire TaskPlanner UI
- [x] Livewire AgentCreator UI
- [x] Create structured code documentation (docs/codemap)
- [x] Implement multi-provider AI system with OpenAI and Anthropic support
- [x] Add agent type categorization and parameter customization

## Missing Tests for Existing Components
- [ ] Create LogoutTest.php for Livewire/Actions/Logout.php
- [ ] Create AppearanceTest.php for Livewire/Settings/Appearance.php
- [ ] Create DeleteUserFormTest.php for Livewire/Settings/DeleteUserForm.php

## Phase 2: Vector Search & Relationships
- [ ] Enable `vector` extension in PostgreSQL
- [ ] Create `vector_chunks` table and migrations
- [ ] Implement text chunking service
- [ ] Develop embedding generation service
- [ ] Integrate semantic search API endpoints
- [ ] Create `edges` table and relationship APIs

## Phase 3: Versioning & Advanced Features
- [ ] Implement content versioning (`content_versions` table)
- [ ] Add tagging system (`tags` and `tag_items` tables)
- [ ] Build version history and restoration APIs

## Phase 4: API Refinement & Cross-App Integration
- [ ] API authentication (Laravel Sanctum)
- [ ] Rate limiting and API gateway
- [ ] Generate OpenAPI documentation
- [ ] Develop PHP SDK and webhooks
- [ ] Recommendation engine and graph visualization

## Phase 5: AI-Assisted Workflow Components
- [x] Livewire TaskPlanner UI
- [x] Livewire AgentCreator UI