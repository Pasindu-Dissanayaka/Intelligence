# 🧠 Intelligence

A secure, multi-user chat application built with **LeafPHP**, powered by **OpenAI GPT**. Users can register/login, chat with GPT-4.1 securely, and view full encrypted chat history and usage analytics.

---

##  Features

-  JWT-based Authentication (via HTTP-only cookies)
-  Encrypted Message Storage (Eloquent mutators)
-  Chat with GPT-3.5 (OpenAI API)
-  Real-Time Usage Analytics (daily history, token breakdowns)
-  Interactive Dashboard with Chart.js
-  Secure REST API endpoints (user-specific access only)
-  Designed for multi-user, scalable architecture

---

##  Tech Stack

- **Backend:** LeafPHP (w/ Illuminate Eloquent), OpenAI API
- **Auth:** JWT RS256 with token refresh
- **Frontend:** HTML + Bootstrap + AlpineJS + Chart.js
- **Database:** MySQL or MariaDB (with JSON & encryption support)

---

## ⚙️ Deployment instructions

### 1. Clone and Install

```bash
git clone https://github.com/Pasindu-Dissanayaka/intellgence.git
cd intellgence
composer install
```

---

### 2. Environment Setup

Create a `.env` file in the project root:

```env
APP_ENV=production
APP_KEY=base64:GENERATED_KEY

# OpenAI Key
OPENAI_API_KEY=your-openai-api-key-here
MESSAGE_ENCRYPTION_KEY==add-aes-256-cbc-key-here

# DB Config
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=DB_NAME
DB_USERNAME=DB_USERNAME
DB_PASSWORD=DB_PASSWORD
```
---

### 3. Generate and Rotate RS256 JWT Keys

To use RS256 for JWT signing:

```bash
mkdir -p storage/jwt

# Generate private key
openssl genpkey -algorithm RSA -out storage/jwt/private.key -pkeyopt rsa_keygen_bits:2048

# Extract public key
openssl rsa -pubout -in storage/jwt/private.key -out storage/jwt/public.key
```

---

### 4. Generate an AES-256-CBC key

To use AES-256 for Message Encryption:

```bash
openssl rand -base64 32
```
Copy the above output into your ENV file
Or use the one in the sample for a demo

DO NOT USE THAT KEY IN PROD EITHER (-_-)

---

### 5.  Migrate the Database

```bash
php leaf db:migrate
```

---

### 6. 📡 Serve the App (Dev)

```bash
php leaf serve
```

Or configure your **web server (Nginx/Apache)** to point to the `/public` directory.



## System dependencies

To run this application, you need to have:

- PHP 8.2 or higher
- Composer


## ⚠️ Disclaimer

This project was developed solely for the purpose of a technical assessment as part of a job interview.  
It is **not intended for production use** or commercial deployment without my **explicit written permission**.

All rights to the design, architecture, and source code remain with the author.  
Reproduction, distribution, or unauthorized use of any part of this application is strictly prohibited.

If you would like to discuss this project further or gain permission to use it,  please contact me directly.