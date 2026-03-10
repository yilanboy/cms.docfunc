# DocFunc CMS

A CMS for my blog project - [DocFunc](https://docfunc.com).

## Tech Stack

- SvelteKit
- Inertia.js
- Laravel
- Tailwind CSS

## Installation

Clone the project.

```bash
git clone https://github.com/yilanboy/cms.docfunc.git
```

Install backend dependencies.

```bash
composer install
```

Install frontend dependencies and build assets.

```bash
pnpm install
pnpm run build
```

Setup environment.

```bash
cp .env.example .env

# Generate key
php artisan key:generate

# Fill up necessary value in .env file
```

Run database migration.

```bash
php artisan migrate --seed
```

## Development

Run the development server.

```bash
pnpm run dev
```
