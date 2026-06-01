# Admin Panel User Manual

Welcome to the Admin Panel User Manual. This document provides a detailed, line-by-line explanation of the essential features and functionality within the system.

## Table of Contents
1. [Introduction](#introduction)
2. [Authentication](#authentication)
3. [Dashboard](#dashboard)
4. [Menu Management](#menu-management)
5. [Modules & Permissions](#modules--permissions)
6. [Role Management](#role-management)
7. [User Management](#user-management)
8. [Profile Settings](#profile-settings)

---

## Introduction
The system is built on the Laravel framework. Despite the directory name `ci4_admin_panel`, the file structure (`app/Http`, `routes/web.php`, `artisan`, `blade.php` views) indicates it is a Laravel application. It is primarily built to serve as an Administrative Dashboard to control users, roles, permissions, and dynamic menus.

---

## Authentication

The Authentication system handles user access.

*   **Login (`/login` & `/`)**: Validates user credentials against the database. It handles both GET (displays the form) and POST (submits credentials) requests.
*   **Sign Up (`/signup` & `/ajax-signup`)**: Allows new administrators or users to register. If successful, users are redirected or alerted via an AJAX response.
*   **Logout (`/logout`)**: Ends the user session and redirects back to the login screen.

---

## Dashboard

*   **URL:** `/dashboard`
*   **Functionality:** Acts as the landing page after a successful login. It typically displays a summary of statistics, quick links, and welcomes the logged-in user. Access is restricted by the `auth` middleware, preventing unauthorized access.

---

## Menu Management

The application features a dynamic menu builder.

*   **URL:** `/menu-editor`
*   **Functionality:** Provides a graphical interface to rearrange, rename, and nest navigation links.
*   **Endpoints:**
    *   `/menu/load`: Fetches the current menu structure (often from an underlying JSON or Database schema, e.g., `menu.json`).
    *   `/menu/save`: Updates the system with the newly rearranged menu structure.

---

## Modules & Permissions

This system uses a modular access control approach.

*   **URL:** `/modules`
*   **Functionality:** Lists all available application modules (e.g., Users Module, Roles Module).
*   **Permissions Matrix:**
    *   `/modules/permissions/get`: Retrieves the current system permissions assigned to specific modules.
    *   `/modules/permissions/save`: Updates and secures the new permissions mapped to the modules.

---

## Role Management

Roles define groups of permissions that can be assigned to users.

*   **List Roles (`/roles`)**: Displays a table of all existing roles.
*   **Create Role (`/roles/create` & `/roles/store`)**: Provides a form to name and create a new role.
*   **Edit Role (`/roles/edit/{id}` & `/roles/update/{id}`)**: Allows modifying an existing role's name or metadata.
*   **Delete Role (`/roles/delete/{id}`)**: Removes a role from the database.
*   **Set Permissions (`/roles/setpermission/{id}` & `/roles/savepermissions/{id}`)**: A crucial feature that maps specific module permissions (Create, Read, Update, Delete) to a specific role.

---

## User Management

Manage individuals who have access to the dashboard.

*   **List Users (`/users`)**: Displays all registered system users in a table.
*   **Create User (`/users/create` & `/users/store`)**: Form to add new users, set their passwords, and assign a default role.
*   **Edit / Delete User (`/users/edit/{id}`, `/users/update/{id}`, `/users/delete/{id}`)**: Modify user properties or remove their access entirely.
*   **User-Specific Permissions (`/users/permissions/{id}` & `/users/savepermissions/{id}`)**: Overrides or supplements the role-based permissions with custom permission rules for a specific individual user.

---

## Profile Settings

Logged-in users can manage their personal profiles.

*   **Edit Profile (`/profile` & `/profile/update/{id}`)**: Allows a user to edit their name, email, and personal information.
*   **Change Password (`/change-password`)**: A secure form requiring the user to provide their old password before setting and confirming a new password.

---

## Code Base Notes
*   **Middleware:** The system uses an `AuthCheck` custom middleware or default `auth` middleware across administrative routes to ensure secure routing.
*   **Views:** UI templates reside in `resources/views/`. Components such as `sidebar.blade.php`, `topbar.blade.php`, and `toast.blade.php` are used modularly across pages.
*   **Styling:** Static assets (CSS/JS) and Bootstrap layouts are loaded via the `public/assets/` directory.

---
_Generated for admin verification._