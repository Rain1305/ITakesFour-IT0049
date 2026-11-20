# Point-of-Sale (POS) System — CodeIgniter 4

A web-based Point-of-Sale (POS) and inventory management system developed using **PHP (CodeIgniter 4)**, **Bootstrap 5**, and **MySQL** for the IT0049 Final Project.

## Features

- **Staff Authentication & Route Protection:** Session-based login and logout using hashed passwords (`password_hash` / `password_verify`) and an `AuthFilter` that restricts access to all management pages to authenticated staff.
- **Product Management:** Full CRUD (Create, Read, Update, Delete) for products, including uploaded product images prepared for display and stock quantity tracking.
- **Customer Management:** Full CRUD for managing customer records.
- **Staff (User) Management:** Full CRUD for managing staff accounts with avatar image uploads (`200x200` square preparation) and hashed passwords.
- **Record Sale:** Enables logged-in staff to select a product, optionally select a customer, and specify a quantity—automatically deducting `stock_quantity` upon submission and preventing orders that exceed available stock.
- **Sales History:** Lists past transactions with the product, customer (or walk-in fallback), staff member, quantity, total price, and date.

## Tech Stack

- **Backend:** PHP 8.x, CodeIgniter 4
- **Frontend:** HTML5, Bootstrap 5, JavaScript
- **Database:** MySQL (`pos_finals`)

## Database Structure (`pos_finals`)

- **`products`**: `id`, `name`, `price`, `stock_quantity`, `image`, `created_at`
- **`customers`**: `id`, `full_name`, `email`, `phone`, `created_at`
- **`users`**: `id`, `username`, `full_name`, `password`, `avatar`, `created_at`
- **`sales`**: `id`, `product_id`, `customer_id`, `sold_by`, `quantity`, `total_price`, `created_at`

## Local Installation & Setup

1. **Clone the repository** into your local web server directory (e.g., `C:\xampp\htdocs\pos-finals`):
   ```bash
   git clone [https://github.com/Rain1305/ITakesFour-IT0049.git](https://github.com/Rain1305/ITakesFour-IT0049.git) pos-finals
   cd pos-finals

2. Install Composer dependencies:
    Bash

    composer install

3. Configure environment variables:

        Copy env to .env

        Set CI_ENVIRONMENT = development

        Set your MySQL database connection (database.default.database = pos_finals)

4. Import the database:

        Open phpMyAdmin (http://localhost/phpmyadmin) and create a database named pos_finals.

        Import the provided pos_finals.sql file into pos_finals.

5. Run the development server:
    Bash

    php spark serve

    Open http://localhost:8080 in your browser.

Default Admin Account

    Username: admin

    Password: password
