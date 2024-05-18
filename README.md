# Rate-App

This project is a simple API application that provides current USD to UAH exchange rates and allows users to subscribe to receive updates on the exchange rate.

## Table of Contents

- [Installation](#installation)
- [API Endpoints](#api-endpoints)
  - [Get Current Exchange Rate](#get-current-exchange-rate)
  - [Subscribe Email](#subscribe-email)
- [Project Structure](#project-structure)
- [Docker Setup](#docker-setup)

## Installation

To run this project locally, you need to have Docker and Docker Compose installed on your machine. Follow these steps to get started:

1. Clone the repository:
    ```bash
    git clone https://github.com/webaction33/rate-app.git
    cd rate-app
    ```

2. Build and start the Docker containers:
    ```bash
    docker-compose up --build
    ```

3. The application will be available at `http://localhost:8000`.

## API Endpoints

### Get Current Exchange Rate

- **URL:** `/api/rate`
- **Method:** `GET`
- **Description:** Returns the current exchange rate from USD to UAH.
- **Responses:**
  - `200 OK`: Returns the current exchange rate as a number.
  - `400 Bad Request`: Invalid status value.

#### Example Request
```http
GET /api/rate HTTP/1.1
Host: localhost:8000
```

#### Example Response
```http
{"rateBuy":39.33,"rateSell":39.8295}
```

### Subscribe Email

- **URL:** `/api/subscribe`
- **Method:** `POST`
- **Description:** Subscribes an email address to receive updates on the exchange rate. The email address will be checked against the existing database to ensure it is not already subscribed.
- **Request Parameters::**
  - `email` (string, required): The email address to subscribe.
- **Responses:**
  - `200 OK`: Email successfully subscribed.
  - `409 Conflict`: Email is already in the database.
  
#### Example Request
```http
POST /api/subscribe HTTP/1.1
Host: localhost:8000
Content-Type: application/x-www-form-urlencoded

email: example@example.com
```

#### Example Response
```http
{
  "message": "E-mail added"
}
```

### Daily Email Update

This application also includes functionality to send an email to each subscribed email address with the latest USD to UAH exchange rate once a day at 07:00 UTC time.

#### How It Works
The script `scripts/send_emails.php` fetches the latest exchange rate from the `/api/rate` endpoint.
The script then sends an email to all subscribed email addresses with the current exchange rate.
This script is scheduled to run daily at midnight using a cron job defined in `crontab.txt`.

### Project Structure
```plaintext
├── api/
│   ├── rate.php
│   └── subscribe.php
├── scripts/
│   └── send_emails.php
├── database/
│   └── database.sqlite
├── vendor/
│   └── (dependencies installed by Composer)
├── .htaccess
├── composer.json
├── composer.lock
├── 000-default.conf
├── Dockerfile
├── docker-compose.yml
├── crontab.txt
├── index.php
└── README.md
```

### Docker Setup

The application is containerized using Docker. The `Dockerfile` sets up the PHP environment with Apache, enables the necessary modules, installs dependencies, and sets the appropriate permissions.

#### Dockerfile
The `Dockerfile` includes the following steps:

1. Use the official PHP image with Apache.
2. Enable `mod_rewrite` for Apache.
3. Install necessary PHP extensions and Composer.
4. Copy the application files and custom Apache configuration.
5. Install PHP dependencies using Composer.
6. Set up a cron job to run the email script daily.
7. Set the appropriate file permissions.
8. Expose port 80.
9. Start the Apache server and cron daemon.

#### Docker Compose
The `docker-compose.yml` file defines the web service and maps port 80 in the container to port 8000 on the host machine. It also mounts the project directory to `/var/www/html` inside the container.

To start the application, run:

```bash
    docker-compose up --build
```

This will build the Docker image and start the container, making the application available at `http://localhost:8000`.

To stop the application, run:

```bash
    docker-compose down
```

If vendor dir not created during the Docker build process and dependencies are not installed automatically, do it manually running:
```bash
docker-compose run web composer install
```