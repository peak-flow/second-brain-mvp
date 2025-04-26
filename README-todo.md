# TODO

## Phase 1: Foundation
- [x] Projects API endpoints and Feature tests
- [x] Tree API endpoints and Feature tests
- [x] Tasks API endpoints and tests
- [x] Topics API endpoints and tests
- [x] Tree manipulation: move and delete nodes
- [x] Endpoint to fetch subtree or depth-limited view
-- [ ] Livewire TreeManager UI for node management
-- [ ] Livewire TaskPlanner UI
-- [ ] Livewire AgentCreator UI

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