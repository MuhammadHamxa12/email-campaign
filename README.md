
# 📧 Laravel Email Campaign Manager Package

A modular **Laravel Package** to manage automated email campaigns with dynamic templating, queuing, retry logic, and filtered targeting. Built with scalability in mind, it can handle both small and large customer datasets.

---

## 🚀 Features

- Create & manage email campaigns
- Filter recipients by status & plan expiry
- Queue-based email delivery (jobs)
- Retry failed/bounced emails with retry count
- Custom HTML email templates with dynamic variables
- Modular Laravel package structure
- Seamless database migration from package
- REST API support with token-based auth

---

## 📁 Directory Structure

```
packages/
└── EmailCampaign/
    └── Manager/
        ├── Controllers/
        │   └── Api/
        ├── Console/
        ├── Jobs/
        ├── Mail/
        ├── Models/
        ├── Resources/
        │   └── views/
        └── database/
            └── migrations/
```

---

## 🛠 What We've Built

- **Campaign Creation:** Title, Subject, Body (HTML)
- **Customer Filtering:** By `status` and `days_to_expire`
- **Queued Email Sending:** `SendCampaignEmailJob`
- **Retry Failed Emails:** Command: `campaign:retry-failed`
- **Dynamic Email Template:** Blade rendering with custom variables
- **API Controller:** Handles validation, queuing, logging
- **Error Handling:** Mail failures stored with `retry_count` for tracking

---

## 🧪 Testing the API

Use **Postman** or **cURL** to test the following endpoints.

### 🔐 Authentication (API Token)

Currently, there is **no authentication** middleware implemented. To add it:

```php
// Example (optional)
Route::middleware('auth:sanctum')->group(function () {{
    Route::post('/campaigns', ...);
}});
```

---

## 📡 API Endpoints

### ✅ Create Campaign
`POST /api/campaigns`

**Body:**
```json
{{
  "title": "Summer Promo",
  "subject": "Big Discounts",
  "body": "<h1>Hello {{ name }}</h1><p>Enjoy your summer discount</p>"
}}
```

---

### 📤 Send Campaign Emails
`POST /api/campaigns/{{id}}/send`

**Body:**
```json
{{
  "status": "Paid",
  "days_to_expire": 5
}}
```

---

## 🔁 Retry Failed Emails

```bash
php artisan campaign:retry-failed
```

Automatically retries all logs where `status = failed OR bounced`.

Now supports `retry_count` field.

---
