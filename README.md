# Binary Tree Simulator — Laravel

Web application that simulates a **binary referral tree**: each user can have at most two children (left and right), new users fill the referrer's first open slot, and points are aggregated per leg.

## Features

- **Guided placement** — the form suggests the first node (by ID) with an open slot, and the new user fills the referrer's left side first, then the right.
- **Points per leg** — recursive calculation of total points on the left and right sides of the root node.
- **Visual tree** — the tree is rendered recursively with Blade partials.
- **Validation** — request validation for name, referrer and points.
- **Reset** — clears all users to restart the simulation.

## Tech Stack

| Layer | Technology |
|---|---|
| Back-end | PHP, Laravel (Eloquent, Blade, request validation) |
| Database | MySQL 5.7 |
| Cache / session / queue | Redis |
| Infrastructure | Docker Compose (PHP-FPM, Nginx, MySQL, Redis, phpMyAdmin) |

## How It Works

| Route | Method | Action |
|---|---|---|
| `/` | GET | Renders the tree and the left/right point totals |
| `/register` | POST | Creates a user and attaches it to the referrer's left or right slot |
| `/clearusers` | DELETE | Truncates the users table |

The tree is modeled on the `users` table through self-referencing relationships (`referrer_id`, `left_child_id`, `right_child_id`), and the leg totals are computed recursively in the `User` model.

## Getting Started

```sh
git clone https://github.com/lucasvil4r/binary-tree-laravel.git
cd binary-tree-laravel
cp .env.example .env              # adjust DB_* and REDIS_* if needed

docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

Open <http://localhost:8989>.
