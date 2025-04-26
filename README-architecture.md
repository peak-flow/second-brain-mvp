$
## Livewire Tree Manager Component

17. Created `TreeManager` Livewire component (app/Livewire/TreeManager.php):
    - Public properties: `$nodes`, `$name`, `$parent_id`
    - Methods: `createNode()`, `moveNode(id, parent_id)`, `deleteNode(id)`
      leveraging existing API logic to maintain `path` and `depth`.

18. Blade view (`resources/views/livewire/tree-manager.blade.php`):
    - Form to add nodes, dropdown to select parent, buttons to delete/move nodes.

19. Tests for Livewire component (`tests/Feature/TreeManagerTest.php`):
    - Assert initial empty state
    - Create and delete operations update both UI and database.
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

## Task API Endpoints (Phase 1)

7. Created `tasks` table migration (`create_tasks_table`):
   - Fields: id, title, description, status, due_date, timestamps

8. Added `Task` Eloquent model with `$fillable` and `$casts`, plus `TaskFactory`.

9. Implemented `TaskController` (API):
   - `index()`: returns all tasks
   - `store()`: validates `title`, `description`, `status`, `due_date`; returns HTTP 201

10. Registered routes in `routes/web.php`:
   - GET `/api/tasks`
   - POST `/api/tasks`

11. Run `vendor/bin/pest` to confirm Task API tests are passing.

## Topic API Endpoints (Phase 1)

12. Created `topics` table migration (`create_topics_table`):
    - Fields: id, title, content, timestamps

13. Added `Topic` Eloquent model with `$fillable`, plus `TopicFactory`.

14. Implemented `TopicController` (API):
    - `index()`: returns all topics
    - `store()`: validates `title`, `content`; returns HTTP 201

15. Registered routes in `routes/web.php`:
    - GET `/api/topics`
    - POST `/api/topics`

16. Run `vendor/bin/pest` to confirm Topic API tests are passing.