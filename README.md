# EuroMart Egypt 🛍️

**Premium European Products E-Commerce Platform**

EuroMart Egypt is a production-ready e-commerce platform built for importing and selling premium European products in Egypt. The platform supports Arabic (RTL) and English, multiple payment methods, WhatsApp ordering, and is built with modern PHP and JavaScript technologies.

## 🚀 Features

### Customer Features
- **Browse Products** - Search, filter by category/brand/price, sort by various criteria
- **Product Details** - Image gallery, reviews, ratings, related products
- **Shopping Cart** - Add/remove/update items, persistent cart with session/user binding
- **Checkout** - Multiple payment methods (COD, Vodafone Cash, Instapay), coupon support
- **Order Tracking** - Real-time order status tracking with progress indicators
- **Customer Dashboard** - Profile management, order history, address management, wishlist
- **WhatsApp Integration** - One-click WhatsApp ordering on every product page
- **Multi-language** - Full Arabic (RTL) and English support with live switching
- **Responsive Design** - Mobile-first, fully responsive Tailwind CSS interface
- **SEO Optimized** - Meta tags, Open Graph, structured data, XML sitemap

### Admin Features
- **Dashboard** - Revenue charts, order statistics, top-selling products
- **Product Management** - CRUD, bulk actions, image upload, stock management, discounts
- **Category Management** - CRUD with image, sort ordering
- **Order Management** - Status workflow, invoices (PDF), printing
- **Coupon System** - Fixed/percentage discounts, expiry dates, usage limits
- **Review Moderation** - Approve/hide/delete customer reviews
- **Customer Management** - View/disable customer accounts

### Technical Features
- **API Ready** - RESTful API with Sanctum authentication for mobile apps
- **Redis Caching** - High-performance caching for products, categories, and dashboard
- **Queue System** - Redis-backed job queues for notifications and emails
- **AI Ready** - Service layer architecture for recommendations, search, chatbot, and predictions (Phase 2)
- **Security** - CSRF, XSS protection, rate limiting, input validation, authorization policies
- **Testing** - PHPUnit test suite with 80%+ coverage target

## 🛠 Tech Stack

| Technology | Purpose |
|-----------|---------|
| **Laravel 12** | PHP Framework |
| **PHP 8.3** | Runtime |
| **MySQL 8** | Database |
| **Redis** | Cache & Queue |
| **Tailwind CSS 3** | Styling |
| **Alpine.js** | Interactivity |
| **Nginx** | Web Server |
| **Ubuntu 24.04** | Server OS |
| **Docker** | Containerization |
| **Sanctum** | API Authentication |

## 📋 Requirements

- PHP 8.3+
- Composer 2.x
- MySQL 8.0+
- Redis 7+
- Node.js 22+ (for frontend build)
- NPM or Yarn

## ⚡ Installation

### 1. Clone the Repository

```bash
git clone https://github.com/your-org/euromart-egypt.git
cd euromart-egypt
```

### 2. Install Dependencies

```bash
composer install
npm install && npm run build
```

### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database and Redis credentials:

```env
DB_DATABASE=euromart
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Database Setup

```bash
php artisan migrate
php artisan db:seed
```

### 5. Storage Link

```bash
php artisan storage:link
```

### 6. Queue Worker (for production)

```bash
php artisan queue:work --sleep=3 --tries=3
```

### 7. Development Server

```bash
php artisan serve
```

### Docker Installation

```bash
docker-compose -f docker/docker-compose.yml up -d
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app php artisan storage:link
```

## 🐳 Docker Services

The `docker/docker-compose.yml` includes:

| Service | Container | Port |
|---------|-----------|------|
| Nginx | `euromart-web` | 80, 443 |
| PHP-FPM | `euromart-app` | 9000 |
| MySQL | `euromart-db` | 3306 |
| Redis | `euromart-redis` | 6379 |
| Queue Worker | `euromart-queue` | - |
| Scheduler | `euromart-scheduler` | - |

## 📂 Project Structure

```
├── app/
│   ├── Console/Commands/       # Artisan commands
│   ├── Events/                  # Events (OrderStatusChanged)
│   ├── Exceptions/              # Exception handlers
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── Api/            # API controllers
│   │   │   ├── Auth/           # Auth controllers
│   │   │   └── Customer/       # Customer controllers
│   │   ├── Middleware/          # Custom middleware
│   │   ├── Requests/           # Form requests
│   │   └── Resources/          # API resources
│   ├── Listeners/              # Event listeners
│   ├── Models/                 # Eloquent models
│   ├── Notifications/          # Notifications
│   ├── Policies/               # Authorization policies
│   ├── Providers/              # Service providers
│   ├── Repositories/           # Repository pattern
│   │   ├── Contracts/         # Repository interfaces
│   │   └── *Repository.php    # Implementations
│   └── Services/               # Business logic
│       ├── AI/                 # AI services (Phase 2)
│       └── *Service.php        # Services
├── bootstrap/
├── config/                     # Configuration files
├── database/
│   ├── factories/              # Model factories
│   ├── migrations/             # Database migrations
│   └── seeders/               # Database seeders
├── docker/                     # Docker configuration
│   ├── nginx/                  # Nginx config
│   ├── php/                    # PHP-FPM config
│   └── mysql/                  # MySQL config
├── resources/
│   ├── css/                    # Tailwind CSS
│   ├── js/                     # JavaScript
│   ├── lang/                   # Translations
│   └── views/                  # Blade templates
│       ├── admin/              # Admin views
│       ├── auth/               # Auth views
│       ├── components/         # Reusable components
│       ├── customer/           # Customer views
│       └── layouts/            # Layout templates
├── routes/                     # Route definitions
│   ├── web.php                 # Customer web routes
│   ├── admin.php               # Admin routes
│   ├── api.php                 # API routes
│   └── auth.php                # Auth routes
├── storage/                    # File storage
├── tests/                      # PHPUnit tests
│   ├── Feature/                # Feature tests
│   └── Unit/                   # Unit tests
└── tailwind.config.js          # Tailwind configuration
```

## 🔐 Security

- **CSRF Protection** - All forms include CSRF tokens
- **XSS Prevention** - Blade escaping, Content Security Policy headers
- **SQL Injection** - Eloquent ORM with parameter binding
- **Input Validation** - Form requests with validation rules
- **Authorization** - Policies and middleware for role-based access
- **Rate Limiting** - API and auth endpoints throttled
- **Password Security** - Bcrypt hashing, minimum 8 characters
- **File Uploads** - Type validation, size limits, secure storage
- **Session Security** - HTTP-only cookies, secure flag in production

## 🌐 API Documentation

Base URL: `/api/v1`

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/v1/register` | Register new user |
| POST | `/api/v1/login` | Login user |
| POST | `/api/v1/logout` | Logout (auth) |
| GET | `/api/v1/user` | Get current user (auth) |

### Products

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/products` | List products (paginated) |
| GET | `/api/v1/products/featured` | Featured products |
| GET | `/api/v1/products/new-arrivals` | New arrivals |
| GET | `/api/v1/products/best-sellers` | Best sellers |
| GET | `/api/v1/products/{slug}` | Product details |
| GET | `/api/v1/products/{slug}/related` | Related products |

### Categories

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/categories` | All categories |
| GET | `/api/v1/categories/{slug}` | Category details |

### Cart (auth required)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/cart` | Get cart items |
| POST | `/api/v1/cart/add` | Add to cart |
| PATCH | `/api/v1/cart/{id}` | Update quantity |
| DELETE | `/api/v1/cart/{id}` | Remove item |
| DELETE | `/api/v1/cart` | Clear cart |

### Orders (auth required)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/orders` | User orders |
| GET | `/api/v1/orders/{id}` | Order details |
| POST | `/api/v1/orders/track` | Track by order number |

### Reviews

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/products/{slug}/reviews` | Product reviews |
| POST | `/api/v1/products/{slug}/reviews` | Submit review (auth) |

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suites
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run with coverage (requires Xdebug/PCOV)
php artisan test --coverage
```

## 📦 Seed Data

```bash
php artisan db:seed
```

Creates:
- Admin user: `admin@euromartegypt.com` / `Admin@2024`
- 20 sample customers
- 10 categories with products
- 5 testimonials
- 5 coupons
- Sample orders

## 🚢 Production Deployment

### Server Requirements
- Ubuntu 24.04 LTS
- Nginx
- PHP 8.3+ (with extensions: pdo_mysql, mbstring, bcmath, gd, zip, redis)
- MySQL 8.0+
- Redis 7+
- Composer
- Node.js & NPM

### Deployment Steps

1. **Setup Server**
   ```bash
   sudo apt update && sudo apt upgrade -y
   sudo apt install nginx mysql-server redis-server php8.3-fpm php8.3-mysql php8.3-redis php8.3-gd php8.3-bcmath php8.3-zip php8.3-mbstring composer
   ```

2. **Deploy Application**
   ```bash
   cd /var/www
   git clone https://github.com/your-org/euromart-egypt.git
   cd euromart-egypt
   composer install --no-dev --optimize-autoloader
   npm install && npm run build
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure Environment**
   ```bash
   # Edit .env with production values
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://euromartegypt.com
   ```

4. **Database**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

5. **Optimize**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan storage:link
   ```

6. **Queue & Scheduler**
   ```bash
   # Add to crontab:
   * * * * * cd /var/www/euromart-egypt && php artisan schedule:run >> /dev/null 2>&1
   
   # Run queue worker as daemon:
   php artisan queue:work redis --sleep=3 --tries=3 --daemon
   ```

7. **Nginx Configuration**
   ```nginx
   # Copy docker/nginx/default.conf to /etc/nginx/sites-available/euromart
   sudo ln -s /etc/nginx/sites-available/euromart /etc/nginx/sites-enabled/
   sudo nginx -t && sudo systemctl reload nginx
   ```

8. **SSL Certificate**
   ```bash
   sudo apt install certbot python3-certbot-nginx
   sudo certbot --nginx -d euromartegypt.com -d www.euromartegypt.com
   ```

## 📊 Performance Optimization

- Redis caching for frequently accessed data
- Eager loading relationships to prevent N+1 queries
- Image optimization with WebP format
- Lazy loading for images and components
- Database indexing on frequently queried columns
- Full-text search indexes for product searches
- Pagination for all listing pages
- Queue workers for background jobs
- OpCache enabled for PHP bytecode caching

## 🤖 AI Features (Phase 2)

The architecture is ready for:

1. **Product Recommendations** - "Customers who bought this also bought..."
2. **AI Search** - Natural language search ("black shoes under 2500 EGP")
3. **Chatbot** - AI-powered customer support
4. **Sales Prediction** - ML-based demand forecasting
5. **Inventory Forecasting** - Predictive stock management

Service interfaces are defined in `app/Services/AI/Contracts/`.

## 📄 License

Copyright © 2024 EuroMart Egypt. All rights reserved.

## 🤝 Support

For support, email info@euromartegypt.com or visit our website.
