# Laravel Product Inventory System

## Overview
This is a Laravel-based product inventory management system created as part of the Laravel Follow-up Skills Test V2. The application allows users to add products with details such as name, quantity, and price, which are stored in a JSON file. Products are displayed in a sortable table with calculated total values and sum totals, with the ability to edit entries inline.

## Features

- **Product Entry Form**: Add products with name, quantity, and price inputs
- **JSON Data Storage**: All product data is stored in a JSON file (`storage/app/products.json`) rather than a database
- **Dynamic Data Display**: Products are shown in a table ordered by submission date (newest first)
- **Automatic Calculations**:
  - Individual product total values (Quantity × Price)
  - Sum total of all products
- **Inline Editing**: Edit existing product entries without page reload
- **Responsive Design**: Twitter Bootstrap 3 for mobile-friendly UI
- **AJAX Operations**: All form submissions and updates happen asynchronously

## Technology Stack

- **Backend**: Laravel PHP framework
- **Frontend**: 
  - HTML5/CSS3
  - Twitter Bootstrap 3.3.7
  - JavaScript/jQuery
- **Data Storage**: JSON file (no database required)
- **Communication**: AJAX for asynchronous operations

## Installation & Setup

1. Clone this repository
2. Run `composer install` to install PHP dependencies
3. Create or update `.env` file with:
   ```
   APP_NAME="Product Inventory"
   APP_ENV=local
   APP_DEBUG=true
   DB_CONNECTION=sqlite
   ```
4. Generate application key: `php artisan key:generate`
5. Ensure storage directories are writable: `chmod -R 755 storage bootstrap/cache`
6. Run `php artisan optimize:clear` to clear caches
7. Create an empty SQLite database: `touch database/database.sqlite`
8. Configure Apache virtual host (for XAMPP users):
   - Edit `C:/xampp/apache/conf/extra/httpd-vhosts.conf` and add:
     ```
     <VirtualHost *:80>
         DocumentRoot "C:/Users/HP/Documents/git_repositories/Laravel-Follow-up-Skills/public"
         ServerName inventory.test
         <Directory "C:/Users/HP/Documents/git_repositories/Laravel-Follow-up-Skills/public">
             Options Indexes FollowSymLinks
             AllowOverride All
             Require all granted
         </Directory>
     </VirtualHost>
     ```
   - Add `127.0.0.1 inventory.test` to your hosts file (`C:/Windows/System32/drivers/etc/hosts`)
   - Restart Apache in XAMPP Control Panel
9. Access the application at http://inventory.test

## Usage

1. Access the application in your browser
2. Fill in the product form with name, quantity, and price
3. Submit to add a new product (table updates automatically)
4. Use the edit button on any row to modify product details
5. All changes are saved instantly to the JSON storage

## Implementation Details

- **JSON Storage**: Used instead of database to demonstrate file-based data handling
- **Data Organization**: Products are sorted by submission date with newest first
- **Form Validation**: Both client-side and server-side validation to ensure data integrity
- **Loading Indicators**: Visual feedback during AJAX operations
- **Responsive UI**: Adapts to different screen sizes for mobile compatibility

## Developer Information

- **Name**: Tanaka Gwese
- **Email**: tanaka.gwese@gmail.com
- **Phone**: +263 77 579 5686

---

Created for Laravel Follow-up Skills Test V2 | © 2025
