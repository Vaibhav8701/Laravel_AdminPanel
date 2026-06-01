# CI4 Admin Panel (Laravel Port) - Full Project Guide

This README explains the full application flow in practical terms:

- Routes and what each one does
- Controllers and method-level logic
- Models, relationships, and data ownership
- Views and front-end behavior
- Helper functions and sidebar/menu rendering
- End-to-end feature flows (auth, role access, user access, profile)

It is written as a maintenance guide so any developer can understand and extend the app safely.

---

## 1) Project Summary

This project is an admin panel built on Laravel, adapted from CI-style patterns.

Main functional domains:

1. Authentication (signup, login, logout)
2. Dashboard
3. Menu editor (stores JSON menu and creates module hierarchy)
4. Module permissions (CRUD-like save/get for permission names)
5. Roles and role-permission mapping
6. Users and user-permission mapping
7. Profile management for logged-in user

Core idea:

- Menu JSON drives module hierarchy.
- Modules have permissions (for example: `users-index`, `users-create`).
- Roles map to permissions via `role_permissions`.
- Users map to roles (`user_roles`) and direct permissions (`user_permissions`).

---

## 2) Tech and Runtime

- PHP + Laravel
- Blade templates
- Eloquent ORM
- jQuery + Bootstrap UI scripts
- MySQL (or compatible relational DB)

---

## 3) High-Level Folder Guide

### Backend

- `routes/web.php`: All web routes
- `app/Http/Controllers`: Request handling per feature
- `app/Models`: DB entity logic and relationships
- `app/helpers.php`: Global helper for menu tree building

### Frontend

- `resources/views/layout`: App shell (sidebar/topbar/main)
- `resources/views/Auth`: Login and signup pages
- `resources/views/menus`, `modules`, `roles`, `Users`, `profile`: Feature pages
- `public/assets/js`: Feature-specific JavaScript logic

---

## 4) Route Map (Complete)

Source: `routes/web.php`

### Public routes

1. `GET /` -> `AuthController@login` (login page)
2. `GET /login` -> `AuthController@login` (login page)
3. `POST /login` -> `AuthController@login` (authenticate)
4. `GET /signup` -> `AuthController@signup` (signup page)
5. `POST /signup` -> `AuthController@signup` (register)
6. `POST /ajax-signup` -> `AuthController@ajaxSignup` (route exists; method may be pending)
7. `POST /logout` -> `AuthController@logout`

### Authenticated route group (`middleware: auth`)

#### Dashboard

1. `GET /dashboard` -> `DashboardController@index`

#### Menu editor

1. `GET /menu-editor` -> `MenuController@index`
2. `GET /menu/load` -> `MenuController@load`
3. `POST /menu/save` -> `MenuController@save`

#### Modules

1. `GET /modules` -> `ModuleController@index`
2. `GET /modules/permissions/get` -> `ModuleController@getPermissions`
3. `POST /modules/permissions/save` -> `ModuleController@savePermission`

#### Roles

1. `GET /roles` -> `RoleController@index`
2. `GET /roles/create` -> `RoleController@create`
3. `POST /roles/store` -> `RoleController@store`
4. `GET /roles/setpermission/{id}` -> `RoleController@setPermission`
5. `POST /roles/savepermissions/{id}` -> `RoleController@savePermissions`
6. `GET /roles/edit/{id}` -> `RoleController@edit`
7. `POST /roles/update/{id}` -> `RoleController@update`
8. `GET /roles/delete/{id}` -> `RoleController@delete`

#### Users

1. `GET /users` -> `UserController@index`
2. `GET /users/create` -> `UserController@create`
3. `POST /users/store` -> `UserController@store`
4. `GET /users/edit/{id}` -> `UserController@edit`
5. `POST /users/update/{id}` -> `UserController@update`
6. `GET /users/delete/{id}` -> `UserController@destroy`
7. `GET /users/permissions/{id}` -> `UserController@permissions`
8. `POST /users/savepermissions/{id}` -> `UserController@savePermissions`

#### Profile

1. `GET /profile` -> `ProfileController@edit`
2. `POST /profile/update/{id}` -> `ProfileController@update`

---

## 5) Controller Logic (Method by Method)

## 5.1 AuthController

File: `app/Http/Controllers/authController.php`

### `signup(Request $request)`

Flow:

1. If request is POST:
2. Validate username, email, phone, password
3. Check duplicate email in `students`
4. Create `Student` with hashed password
5. Return success/failure flash message
6. If request is GET: render signup page

### `login(Request $request)`

Flow:

1. If request is POST:
2. Validate email/password
3. `Auth::attempt($credentials)`
4. On success: regenerate session + redirect dashboard
5. On failure: flash invalid credentials
6. If request is GET: render login page

### `logout(Request $request)`

Flow:

1. `Auth::logout()`
2. Invalidate + regenerate CSRF token
3. Redirect to login

## 5.2 DashboardController

File: `app/Http/Controllers/DashboardController.php`

### `index()`

- Returns `dashboard` Blade view.

## 5.3 MenuController

File: `app/Http/Controllers/MenuController.php`

### `index()`

- Returns menu editor page.

### `load()`

Flow:

1. Read JSON from `resources/views/menus/menu.json`
2. Decode JSON array
3. Pretty-print JSON
4. Truncate `menus` table
5. Insert one active `menus` record with `full_json`
6. Return decoded JSON as response

Purpose:

- Keeps DB copy synchronized with JSON file
- Supplies JSON back to UI editor

### `save(Request $request)`

Flow:

1. Read posted `json`
2. Validate JSON parse
3. Pretty-print
4. Save to JSON file
5. Truncate + insert into `menus` table
6. Rebuild `modules` table by calling `module($decoded)` recursively
7. Return JSON success/failure response

### `module($decoded, $parentModuleId = 0)`

Flow:

1. First call truncates `modules`
2. Insert each item as module (`name`, `permission`, `deletestatus`, `is_active`, `parent_id`)
3. Recurse for children with inserted module ID as parent

### `jsonResponse($success, $message)`

- Standard small helper for JSON response payload.

## 5.4 ModuleController

File: `app/Http/Controllers/ModuleController.php`

### `index()`

- Loads modules ordered by ID and renders `modules.index`.

### `savePermission(Request $request)`

Flow:

1. Receive `module_id` + `permissions[]`
2. Validate required inputs
3. Delete old `module_permissions` for that module
4. Insert each permission row
5. Return JSON success

### `getPermissions(Request $request, $moduleId = null)`

Flow:

1. Read module ID from query param first
2. Validate positive module ID
3. Fetch permissions by module
4. Return JSON payload

## 5.5 RoleController

File: `app/Http/Controllers/RoleController.php`

### `index()`

- Fetch all roles and render list.

### `create()`

- Render create role form.

### `store(Request $request)`

Flow:

1. Validate unique role name
2. Create role with name and description
3. Redirect with success message

### `setPermission($id)`

This is the core access-mapping logic for roles.

Flow:

1. Find role
2. Load all modules (ordered by parent)
3. Load all permissions with module relation
4. Build `moduleHierarchy`:
   - Parent modules
   - Child modules under parents
   - Permissions attached to parent or child
5. Load assigned permission IDs from `role_permissions`
6. Render `roles.setpermission`

### `savePermissions(Request $request, $id)`

Flow:

1. Read selected permission IDs
2. Transaction:
   - Delete old `role_permissions` for role
   - Insert selected rows
3. Redirect with success

### `edit($id)`

- Load one role and render edit form.

### `update(Request $request, $id)`

Flow:

1. Validate unique role name except current role
2. Update name + description
3. Redirect with success

### `delete($id)`

- Delete role by ID and redirect.

## 5.6 UserController

File: `app/Http/Controllers/UserController.php`

### `index()`

- Fetch users with roles relation and render list.

### `create()`

- Fetch all roles and render user create form.

### `store(Request $request)`

Flow:

1. Validate name/email/password
2. Begin transaction
3. Create user (`students`) with hashed password
4. If role selected:
   - Sync role into `user_roles`
   - Copy role permissions from `role_permissions` into user via `user_permissions`
5. Commit + redirect success
6. On exception: rollback + error flash

### `edit($id)`

Flow:

1. Find user
2. Load all roles
3. Detect currently assigned role
4. Render edit form

### `update(Request $request, $id)`

Flow:

1. Find user
2. Begin transaction
3. Update name/email/phone (+password only if provided)
4. If role selected:
   - Sync role
   - Replace user permissions with role permissions
5. Commit + redirect
6. On exception: rollback

### `destroy($id)`

- Delete user and redirect.

### `permissions($id)`

Purpose:

- Open User Access page and pre-check already assigned permissions.

Flow:

1. Find user
2. Load modules + permissions(with module)
3. Build `moduleHierarchy` exactly like role flow
4. Load assigned IDs from `user_permissions.permission_id`
5. Render `users.permissions`

### `savePermissions(Request $request, $id)`

Flow:

1. Find user
2. Read selected permission IDs
3. `permissions()->sync($permissionIds)`
4. Redirect with success message

## 5.7 ProfileController

File: `app/Http/Controllers/ProfileController.php`

### `edit()`

Flow:

1. Get logged-in user from auth session
2. If not authenticated, redirect login
3. Render profile edit view

### `update(Request $request, $id)`

Flow:

1. Get current user
2. Block unauthenticated access
3. Ensure user can only update own profile (`currentUser->id === $id`)
4. Validate name, phone, optional password
5. Hash password if provided
6. Update `students` row
7. Redirect back to profile page with success

---

## 6) Model Layer and Relationships

## 6.1 Student model

File: `app/Models/Student.php`

- Table: `students`
- Auth user model (`Authenticatable`)
- Fillable: `name`, `email`, `phone`, `password`
- Relations:
  - `roles()` many-to-many via `user_roles`
  - `permissions()` many-to-many via `user_permissions`

## 6.2 Role model

File: `app/Models/Role.php`

- Table: `roles`
- Fillable: `name`, `description`
- Relation:
  - `permissions()` many-to-many via `role_permissions`

## 6.3 Permission model

File: `app/Models/Permission.php`

- Table: `module_permissions`
- Fillable: `module_id`, `permission_name`
- Relations:
  - `module()` belongs-to module
  - `roles()` many-to-many via `role_permissions`
- Utility:
  - `getAllWithModules()` returns transformed permission list + module metadata

## 6.4 Module model

File: `app/Models/module.php` (class name `Module`)

- Table: `modules`
- Fillable: menu/module metadata fields
- Self-referencing relations:
  - `parent()` belongs-to module
  - `children()` has-many modules
- Permission relation:
  - `permissions()` has-many `Permission`

## 6.5 RolePermission model

File: `app/Models/RolePermission.php`

- Table: `role_permissions`
- Fillable: `role_id`, `permission_id`
- Relations:
  - `role()` belongs-to role
  - `permission()` belongs-to permission

## 6.6 Menu model

File: `app/Models/Menu.php`

- Table: `menus`
- Stores menu snapshot in `full_json`
- `is_active` controls selected menu config

---

## 7) Helper Logic (Global)

File: `app/helpers.php`

### `getMenus()`

Flow:

1. Load active row from `menus` table
2. Get JSON using `getMenuJson()`
3. Decode JSON
4. Convert to sidebar format using `convertMenuFormat()` recursively
5. Return normalized array

### `getMenuJson($menuRecord)`

- Uses DB `full_json` first
- Falls back to `resources/views/menus/menu.json`

### `convertMenuFormat(array $menu)`

- Converts menu-editor schema (`text`, `href`, etc.) into sidebar schema (`name`, `url`, `children`)

---

## 8) View Layer (What each area does)

## 8.1 Layout

- `resources/views/layout/app.blade.php`
  - Main shell
  - Includes sidebar + topbar
  - Contains `@yield('content')` and `@yield('scripts')`

- `resources/views/layout/sidebar.blade.php`
  - Calls `getMenus()` helper
  - Renders nested nav recursively from array
  - Applies active/open state based on current URL path

- `resources/views/layout/topbar.blade.php`
  - Shows current user name
  - Live time/date script
  - Logout form

## 8.2 Auth views

- `resources/views/Auth/login.blade.php`
  - Login form posting to `login`

- `resources/views/Auth/signup.blade.php`
  - Signup form posting to `signup`
  - Displays validation and flash messages

## 8.3 Dashboard

- `resources/views/dashboard.blade.php`
  - Static KPI cards + chart placeholders
  - Uses `public/assets/js/dashboard.js`

## 8.4 Menu editor

- `resources/views/menus/index.blade.php`
  - jQuery menu-editor integration
  - Loads menu via `/menu/load`
  - Saves edited JSON via `/menu/save`

## 8.5 Module screen

- `resources/views/modules/index.blade.php`
  - Lists modules
  - Opens permission modal
  - Saves module permissions via AJAX

## 8.6 Roles

- `resources/views/roles/index.blade.php`: role list + actions
- `resources/views/roles/create.blade.php`: create role form
- `resources/views/roles/edit.blade.php`: edit role form
- `resources/views/roles/setpermission.blade.php`: permission tree UI with parent/child checkbox logic

## 8.7 Users

- `resources/views/Users/index.blade.php`: user list + edit/access/delete
- `resources/views/Users/create.blade.php`: create user form
- `resources/views/Users/edit.blade.php`: edit user form
- `resources/views/Users/permissions.blade.php`: user access tree and save form

## 8.8 Profile

- `resources/views/profile/edit.blade.php`: current user profile update form

---

## 9) Front-End Scripts and Logic

## 9.1 `public/assets/js/module-permissions.js`

Responsibilities:

1. Open module permission modal
2. Load existing permissions via GET API
3. Add/remove permission rows
4. Save permissions via POST API with CSRF token

## 9.2 `public/assets/js/user-permissions.js`

Responsibilities:

1. Parent checkbox toggles all children and permissions
2. Child checkbox toggles its own permissions
3. Parent/child indeterminate state handling
4. Search filter over module and permission names
5. Initial state sync on page load (reflect already assigned permissions)

## 9.3 `public/assets/js/sidebar.js`

- Handles sidebar UI behavior (submenu toggles and interaction states).

## 9.4 `public/assets/js/dashboard.js`

- Handles dashboard chart rendering behavior.

---

## 10) Database Notes

Observed migrations include:

- `students` table migration in `database/migrations/2026_02_10_000003_create_students_table.php`

Additional tables used by code (should exist in DB schema):

1. `roles`
2. `menus`
3. `modules`
4. `module_permissions`
5. `role_permissions`
6. `user_roles`
7. `user_permissions`

If these are missing, create migrations before deployment.

---

## 11) End-to-End Feature Flows

## 11.1 Login flow

1. User opens `/login`
2. Form posts credentials
3. `Auth::attempt`
4. Session regenerated
5. Redirect to `/dashboard`

## 11.2 Build sidebar menu from menu editor

1. Admin saves menu JSON in menu editor
2. JSON saved to file + DB (`menus.full_json`)
3. Module hierarchy regenerated in `modules`
4. Sidebar calls `getMenus()` and renders latest active menu

## 11.3 Role access flow

1. Open role access page
2. Build module/permission tree
3. Check current permissions from `role_permissions`
4. Save overwrites role mapping transactionally

## 11.4 User access flow

1. Open user access page
2. Build same module/permission tree
3. Read assigned from `user_permissions`
4. Save syncs direct user permissions

## 11.5 User create/update with role sync

1. Assign role
2. System fetches role permissions
3. System syncs those permissions to user

## 11.6 Profile update flow

1. User opens `/profile`
2. Can update own name/phone
3. Optional password update with hash
4. Unauthorized ID update blocked (403)

---

## 12) Important Conventions and Constraints

1. Auth user model is `Student`, not default `User`.
2. Access tree logic depends on module parent-child structure.
3. Sidebar depends on valid menu JSON and helper conversion.
4. User permission page expects `moduleHierarchy` structure from controller.

---

## 13) Current Implementation Notes

1. `AuthController` file is named `authController.php` but class is `AuthController`.
2. Some view folders use uppercase names (`Users`, `Auth`). This can matter on Linux case-sensitive filesystems.
3. Routes reference `ajaxSignup`; ensure the method exists if this route is used.
4. Module view references `assets/js/module-mvc.js`; ensure that file exists if MVC button is used.

---

## 14) Local Setup Quick Steps

1. Install dependencies:

   - `composer install`
   - `npm install` (if front-end build is needed)

2. Configure environment:

   - Copy `.env.example` to `.env`
   - Set DB credentials

3. Generate app key:

   - `php artisan key:generate`

4. Run migrations:

   - `php artisan migrate`

5. Serve app:

   - `php artisan serve`

---

## 15) Where to Start When Debugging

If issue is about:

1. Routing mismatch -> `routes/web.php`
2. Page not loading expected data -> related Controller method
3. Relationship or missing rows -> related Model + pivot table
4. Sidebar not reflecting menu -> `app/helpers.php` + `menus.full_json`
5. Permission checkboxes not syncing -> `public/assets/js/user-permissions.js`

---

## 16) Suggested Next Improvements

1. Add missing migrations for all custom tables
2. Add Form Request classes for validation reuse
3. Add feature tests for auth, role permission, user permission, profile
4. Normalize folder/file naming case across project
5. Replace GET delete routes with DELETE methods + CSRF forms

---

This guide is intentionally detailed so new developers can understand all major logic paths without searching file-by-file.
