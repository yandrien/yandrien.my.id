# Portfolio & CMS - yandrien.my.id

Personal website built with Laravel featuring a blog system, user authentication, and analytics.

**Live Demo:** https://yandrien.my.id

### Features
- Article CRUD with image upload & TinyMCE editor
- Social login via Google & Facebook OAuth
- Visitor analytics: unique visitors, IP, country, bot detection
- Custom multi-language support using Google Translate JS + CSS
- Regional language dictionary with real-time AJAX search
- Responsive UI with Tailwind CSS

### Tech Stack
- **Backend:** PHP 8.2, Laravel 11
- **Frontend:** Blade, Tailwind CSS, jQuery, AJAX
- **Database:** MySQL
- **Tools:** Composer, Git, cPanel

### Screenshots
![Home](public/screenshots/home.png)
![Dashboard](public/screenshots/dashboard.png)

### Setup Locally
1. `git clone https://github.com/yandrien/portfolio.git`
2. `composer install`
3. `cp .env.example .env`
4. `php artisan key:generate`
5. Set database di `.env`
6. `php artisan migrate --seed`
7. `php artisan serve`
