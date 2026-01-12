Usage Billing API (Laravel)
A Laravel-based REST API for tracking usage, generating invoices, and handling payments with Stripe, secured using Laravel Sanctum.

🚀 Features

API authentication using Laravel Sanctum
Record customer usage
Get usage summary
Generate and manage invoices
Pay invoices
Stripe webhook integration for payment events
API versioning (/api/v1)

🛠️ Tech Stack

PHP (Laravel)
Laravel Sanctum (API authentication)
Stripe (payments & webhooks)
MySQL / PostgreSQL (or any Laravel-supported DB)

📦 Requirements

PHP >= 8.1

Composer

MySQL / PostgreSQL

Node.js & NPM (optional)

Stripe account


⚙️ Installation

1️⃣ Clone the repository

git clone https://github.com/masimarif88/usage-billing.git

cd usage-billing

2️⃣ Install dependencies

composer install

3️⃣ Environment setup

cp .env.example .env

php artisan key:generate

Update .env with your database and Stripe credentials:

APP_NAME="Usage Billing API"

APP_ENV=local

APP_KEY=

APP_DEBUG=true

APP_URL=http://localhost

DB_DATABASE=usage_billing

DB_USERNAME=root

DB_PASSWORD=


STRIPE_KEY=pk_test_xxx

STRIPE_SECRET=sk_test_xxx

STRIPE_WEBHOOK_SECRET=whsec_xxx


4️⃣ Run migrations

php artisan migrate

Run Database Seeder for plans and user creation 

php artisan db:seed

5️⃣ Start the server

php artisan serve

API will be available at:

http://localhost:8000/api/v1


🔐 Authentication (Sanctum)
Login
POST /api/v1/login


Request

{
  "email": "user@example.com",
  "password": "password"
}


Response

{
  "token": "your_api_token_here"
}


Use the token in headers:
Authorization: Bearer YOUR_TOKEN

📚 API Endpoints
🔑 Public Routes

| Method | Endpoint                 | Description            |
| ------ | ------------------------ | ---------------------- |
| POST   | `/api/v1/login`          | User login             |
| POST   | `/api/v1/stripe/webhook` | Stripe webhook handler |


🔒 Authenticated Routes (Sanctum)

Usage
| Method | Endpoint                | Description       |
| ------ | ----------------------- | ----------------- |
| POST   | `/api/v1/usage`         | Record usage      |
| GET    | `/api/v1/usage-summary` | Get usage summary |

Body For api/v1/usage

{
    "units": 250
}


Invoices
| Method | Endpoint                         | Description      |
| ------ | -------------------------------- | ---------------- |
| GET    | `/api/v1/invoices`               | List invoices    |
| GET    | `/api/v1/invoices/{invoice}`     | View invoice     |
| POST   | `/api/v1/invoices/{invoice}/pay` | Pay invoice      |
| POST   | `/api/v1/invoices/generate`      | Generate invoice |

Body For Invoice Generate 
{
    "month": "2026-01"
}


💳 Stripe Webhook Setup

1️⃣ Create webhook in Stripe Dashboard
Endpoint URL:
https://your-domain.com/api/v1/stripe/webhook

2️⃣ Select events

Recommended events:

invoice.payment_succeeded

invoice.payment_failed

payment_intent.succeeded

payment_intent.payment_failed

3️⃣ Copy Webhook Secret

Add it to .env:

STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxx


4️⃣ Test Webhook Locally (Optional)

Install Stripe CLI:

stripe listen --forward-to localhost:8000/api/v1/stripe/webhook


Trigger test events:

stripe trigger invoice.payment_succeeded


🧪 API Testing

You can test the API using:

Postman

Insomnia

cURL

Make sure to include:

Authorization: Bearer YOUR_TOKEN


🧹 Git Ignore

Sensitive and generated files are excluded:
.env
vendor/
node_modules/
storage/

📄 License
This project is open-source and available under the MIT License.


👨‍💻 Author

Masim Arif
GitHub: https://github.com/masimarif88