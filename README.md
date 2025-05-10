# 📧 Laravel Email Campaign Manager

This Laravel package enables automated email campaign management with dynamic Blade-based content rendering, customer filtering, queuing, and retry logic. Designed to handle both small and large datasets efficiently through background jobs, it supports clean campaign creation, mail queuing, and error logging.

---

## 🔗 API Endpoints

---

## 🔐 Login API (Token-Based)

### 🔑 Login (Token Generation)
`POST /api/login`

**Body:**
```json
{
  "email": "admin@example.com",
  "password": "password"
}

### ✅ Create Campaign
`POST /api/campaigns`

**Body:**
```json
{
  "title": "Promo",
  "subject": "Special Offer",
  "body": "<h1>Hello {{ name }}</h1><p>Enjoy your offer!</p>"
}

📤 Send Emails to Filtered Customers
POST /api/campaigns/{id}/send

Body:
{
  "status": "Paid",
  "days_to_expire": 5
}

⚙️ Artisan Commands
▶ Run Queue Worker
php artisan queue:work

♻ Retry Failed Emails
php artisan campaign:retry-failed

