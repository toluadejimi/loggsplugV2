# LOGS PLUG – React frontend

React + TypeScript + Vite app using **shadcn/Radix**-style UI. All data comes from the Laravel backend API.

## Environment

Create a `.env` in the frontend directory (see `.env.example`):

- **`VITE_API_URL`** – Backend (Laravel) base URL.
  - **Unset or empty:** API requests use relative `/api` (same origin in production, or Vite proxy in dev).
  - **Set:** e.g. `VITE_API_URL=http://localhost:9090` or `VITE_API_URL=https://api.yourdomain.com` so all API calls go to that host.

The dev server proxy target also uses `VITE_API_URL`, defaulting to `http://localhost:9090`.

## Setup

```bash
npm install
```

## Development

1. Start the Laravel backend (e.g. `php artisan serve` or `php -S localhost:9090` in the `core` directory).
2. In this directory run:

   ```bash
   npm run dev
   ```

3. Open **http://localhost:5173**. Vite proxies `/api` and `/sanctum` to the backend (see `vite.config.ts`; default target is `http://localhost:9090`).

## Build & run on Laravel

The app is meant to be served by Laravel so the frontend “really runs on the backend”:

1. Build:

   ```bash
   npm run build
   ```

2. Copy the build into Laravel’s public directory:

   ```bash
   mkdir -p ../core/public/app
   cp -r dist/* ../core/public/app/
   ```

3. Visit **https://yourdomain.com/app** (or **http://localhost:9090/app**). Laravel serves the SPA from `public/app/` and the React app loads at `/app`.

## API

- **Base URL:** same origin (relative `/api`). In dev, Vite proxies to the Laravel backend.
- **Auth:** Login/register return a **Bearer token**. It is stored in `localStorage` and sent in `Authorization` for protected routes.
- **Endpoints:**  
  `GET /api/categories`, `GET /api/products`, `GET /api/products/:id`,  
  `POST /api/login`, `POST /api/register`, `POST /api/logout`,  
  `GET /api/user`, `GET /api/dashboard`, `GET /api/orders`, `GET /api/orders/:id`,  
  `GET /api/category-products/:id` (auth required).

## Pages

- **/** – Home  
- **/login**, **/register** – Auth  
- **/products** – Product list with search  
- **/product/:id** – Product details  
- **/category/:id** – Category products (auth required)  
- **/dashboard** – User dashboard (auth required)  
- **/orders**, **/orders/:id** – Orders list and detail (auth required)
