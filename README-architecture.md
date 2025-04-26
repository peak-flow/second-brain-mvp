# Architecture Notes

## Tree API Endpoints (Phase 1)

1. Wrote `TreeApiTest` (tests/Feature/TreeApiTest.php) covering:
   - GET `/api/trees` returns an empty array initially
   - POST `/api/trees` creates a new node and returns JSON with expected fields

2. Created migration `create_trees_table`:
   - Fields: id, parent_id (nullable FK), path (materialized path), item_type, item_id, depth, name, timestamps
   - Indexed `path` for efficient ancestor/descendant lookups

3. Added `Tree` Eloquent model with:
   - `$fillable` for mass assignment
   - Relations: `parent()`, `children()`, `descendants()`, polymorphic `item()`

4. Implemented `TreeController` (API):
   - `index()`: returns all nodes
   - `store()`: validates input, computes `depth` and `path`, returns HTTP 201

5. Registered routes in `routes/web.php` under `/api` prefix with CSRF disabled:
   - GET `/api/trees`
   - POST `/api/trees`

6. Run `vendor/bin/pest` to confirm all Tree API tests are green.