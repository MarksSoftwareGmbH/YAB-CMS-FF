# Features

YAB-CMS-FF provides a clean, modern admin interface powered by AdminLTE and a flexible content model built on CakePHP 5.

## Dashboard (Promoted Articles)

The dashboard gives a quick overview of the system after login. It shows promoted articles and recent projects with progress indicators.

![Dashboard](docs/screenshots/dashboard-promoted-articles.png)

- Important notes and announcements
- Recent projects with progress bars
- Quick navigation via the sidebar

## User Profiles

Users have rich profiles that go beyond basic account data. Each profile can contain diary entries, a timeline, and structured contact information.

### Profile Overview

![Profiles](docs/screenshots/profiles.png)

Profiles are displayed as cards showing the number of diary and timeline entries.

### Profile Detail

The profile page is organized in tabs:

- **Diary** – Personal or project-related diary entries  
- **Timeline** – Chronological events (supports rich content including code blocks)  
- **Data** – Structured profile data (name, company, address, website, timezone, etc.)

![Profile Diary](docs/screenshots/profile-diary.png)
![Profile Timeline](docs/screenshots/profile-timeline.png)
![Profile Data](docs/screenshots/profile-data.png)

Diary entries support basic social interactions (likes, views) and sharing options.

## Projects

Projects can be managed and displayed with additional metadata.

### Project List

![Projects](docs/screenshots/projects.png)

### Project Detail

![Project Detail](docs/screenshots/project-detail.png)

- Estimated and spent hours
- Optional Bitcoin-related tracking (satoshis)
- Project description and status
- Project leader assignment

## Authentication

Clean and focused authentication screens.

### Login

![Login](docs/screenshots/login.png)

### Registration

![Registration](docs/screenshots/register.png)

- Password proposal helper
- Simple math captcha
- Terms of service & privacy policy acceptance

## Admin Backend

The admin area is protected by role-based access control (TinyAuth) and provides a clear overview of the system.

### Admin Dashboard

![Admin Dashboard](docs/screenshots/admin-dashboard.png)

The dashboard shows key statistics at a glance:

- Active Articles, Article Types, Attributes and Choices
- Active Domains, Locales, Regions and Countries
- Active Users and Roles

## Content Management (EAV)

YAB-CMS-FF uses a flexible Entity-Attribute-Value (EAV) approach for content. Article Types define the structure, Attributes define the fields, and Values store the actual content per article.

### Articles

![Articles List](docs/screenshots/articles-list.png)

Articles can be filtered by Domain, Locale and Article Type. New articles can be created for predefined types (Article, Blog, Page, Product, Project).

![Edit Article](docs/screenshots/edit-article.png)

The article editor includes:
- Name, Alias, Slug, Excerpt and Body (with WYSIWYG)
- Author, Domain, Locale and Categories
- Promote and Status flags
- Meta description

### Article Types

![Article Types](docs/screenshots/article-types.png)

Predefined types include Article, Blog, Page, Product and Project. Each type can have its own set of attributes.

![Edit Article Type](docs/screenshots/edit-article-type.png)

When editing a type you can select which attributes belong to it (e.g. Project hours, Project progress, Meta description, etc.).

### Article Type Attributes

![Type Attributes](docs/screenshots/type-attributes.png)

Attributes define the available fields. Each attribute has a type (string, text, number, select, …), optional empty value and WYSIWYG flag.

![Edit Type Attribute](docs/screenshots/edit-type-attribute.png)

Attributes can include additional media fields (links, images, videos, PDFs). File uploads are available directly in the attribute editor.

### Attribute Choices

![Attribute Choices](docs/screenshots/attribute-choices.png)

For select-type attributes you can define predefined choices (e.g. salutations or project team members).

## Navigation

### Menus

![Menus](docs/screenshots/menus.png)

Menus (Header, Footer, …) are managed per Domain and Locale. Menu items can be reordered.

### Categories

![Categories](docs/screenshots/categories.png)

Categories are hierarchical and can be managed per Domain and Locale. They support drag-and-drop reordering.

## Users & Roles

![Users](docs/screenshots/users.png)
![Roles](docs/screenshots/roles.png)

Roles include Admin, Manager, Public and Rest. Users are assigned roles and locales.

### Form Validation

![Edit User](docs/screenshots/edit-user.png)

Forms provide immediate visual feedback for required fields.

## Domains

![Domains](docs/screenshots/domains.png)
![Edit Domain](docs/screenshots/edit-domain.png)

Multiple domains are supported. Each domain can have its own theme and assigned locales.

## Settings

![Settings](docs/screenshots/settings.png)

System and theme settings (site title, logo, navbar colors, backend/frontend themes, etc.) are stored per domain.

![Settings Export](docs/screenshots/settings-export.png)
![Settings Import](docs/screenshots/settings-import.png)

Settings support import and export in multiple formats (XLSX, CSV, XML, JSON).

## Technical Highlights

- **Zero-config SQLite**: On first request the system automatically creates the database, runs migrations and seeds, and registers the current host as a domain.
- **EAV content model**: Article Types + Attributes + Values allow flexible content structures without schema changes.
- **Multi-domain & multi-locale** support out of the box.
- **Role-based access** via TinyAuth.
- **Import / Export**: Most entities support CSV, XLSX, XML and JSON import and export.
- **REST API** for most entities.
- **Bot protection** middleware that blocks common scanner paths.

### System

- Logs
- Cache, Log and Session clearing
- Settings

Administrative tools for clearing caches, logs and sessions.

## REST API

Most entities are available via a JSON REST API under the `/api` prefix (articles, article types, categories, users, roles, settings, etc.). Authentication uses a token endpoint (`/api/oauth/token`).

## Authorization

Access is controlled via TinyAuth with clear role definitions:

- **admin** – Full access
- **manager** – Most administrative tasks
- **public** – Frontend and limited profile access

Public routes (login, registration, profile views, promoted articles, etc.) are explicitly allowed.