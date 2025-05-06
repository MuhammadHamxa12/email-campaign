# 📧 Laravel Email Campaign Package

This Laravel package allows you to create email campaigns, filter audiences, and send bulk emails asynchronously via queues, following an **API-first** approach.

---

## ✅ Features

- Create email campaigns (title, subject, body)
- Filter customers by status and days to expiry
- Send emails asynchronously via Laravel queues
- Track delivery status (`pending`, `sent`, `failed`)
- Use SendGrid or any SMTP provider
- Bonus Blade demo for quick UI testing

---

## 🚀 Installation

1. Clone the project:

git clone https://github.com/MuhammadHamxa12/email-campaign.git
cd email-campaign

2. Install dependencies:
composer install
npm install && npm run dev (if you use frontend assets)

3. Copy `.env.example`:
cp .env.example .env

4. Generate app key:
php artisan key:generate

5. Configure `.env`:
- Set **DB** credentials
- Set **MAIL** with SendGrid API key

6. Run migrations:
php artisan migrate

7. Import customers:
mysql -u root -p email_campaign < customers.sql

8. Start queue worker:
php artisan queue:work

9. Serve the app:
php artisan serve


---

## 📱 API Endpoints

| Method | Endpoint                   | Description        |
|--------|----------------------------|--------------------|
| POST   | /api/campaigns             | Create campaign    |
| GET    | /api/campaigns/filter      | Filter customers   |
| POST   | /api/campaigns/send-emails | Send emails        |
| POST   | /api/login                 | API Authentication |

---

## 🔑 Postman Collection

Import the attached file:  
`email-campaign.postman_collection.json`

---

## 🔒 API Authentication

We use **Laravel Sanctum** for API authentication.

1. First, call the login API:

POST /api/login
{
"email": "admin@example.com",
"password": "admin1234"
}

2. Get the Bearer token from the response.

3. Use it in the Authorization header:

Authorization: Bearer YOUR_TOKEN

Make sure Sanctum is set up in Laravel:
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate


## 💥 Bonus Blade Demo

Access the web demo at:
/campaigns


---

## ⚙ Commands

- Migrate DB:

---

## ⚙ Commands

- Migrate DB:

- Start queue worker:
php artisan queue:work

- Serve app:
php artisan serve


---

## 📩 SendGrid Setup

1. Get API Key from SendGrid
2. Set in `.env`:

MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=you@example.com
MAIL_FROM_NAME="Email Campaign"


---

Happy coding! 🚀
