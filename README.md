<p align="center">
    <h1 align="center">POS System Using Laravel</h1>
</p>

## Installation

### Requirements

For system requirements you [Check Laravel Requirement](https://laravel.com/docs/12.x/deployment#server-requirements)

### Clone the repository from github.

    git clone https://github.com/rono516/pos.git [YourDirectoryName]

The command installs the project in a directory named `YourDirectoryName`. You can choose a different
directory name if you want.

### Install dependencies

Laravel utilizes [Composer](https://getcomposer.org/) to manage its dependencies. So, before using Laravel, make sure you have Composer installed on your machine.

    cd YourDirectoryName
    composer install

### Config file

Rename or copy `.env.example` file to `.env` 1.`php artisan key:generate` to generate app key.

1. Set your database credentials in your `.env` file
1. Set your `APP_URL` in your `.env` file.

### Database

1. Migrate database table `php artisan migrate`
1. `php artisan db:seed`, this will initialize settings and create and admin user for you [email: admin@gmail.com - password: admin123]

### Install Node Dependencies

1. `npm install` to install node dependencies
1. `npm run dev` for development or `npm run build` for production

### Create storage link

`php artisan storage:link`

### Run Server

1. `php artisan serve` or Laravel Homestead
1. Visit `localhost:8000` in your browser. Email: `admin@gmail.com`, Password: `admin123`.
 <!-- 1. Online demo: [pos.khmernokor.com](https://pos.khmernokor.com/) -->

### Feature To-Do List

#### 📊 Dashboard
- [x] Display overall sales summary (total revenue, today's sales, top-selling product)

#### 📦 Products
- [x] Product list with pagination, search, and category filters
- [x] Add product form (name, price, stock, image, category)
- [x] Edit/Delete product actions

#### 🛒 Point Of Sale
- [x] Responsive POS interface (for desktop & tablet)
- [x] Add products via barcode scan or name search
- [x] Display cart with items, quantity
- [ ] Support multiple payment methods (cash, card, etc.)
- [ ] Apply discount by specific items
- [ ] Apply discount by invoice (overall discount)
- [ ] Print or download sale receipt

#### 📦 Orders
- [x] List all sales/orders with filters (date)
    - [ ] Add filter (status, customer)
- [x] View detailed order/invoice page
- [ ] Support order returns and refunds

#### 👥 Customers
- [x] Customer list
    - [ ] Filter customer with (name, phone and email)
- [x] Add/Edit customer information (name, phone, email, address)
- [ ] View customer order history

#### 🚚 Supplier
- [x] Supplier list
    - [ ] Filter supplier with (name, phone and email)
- [x] Add/Edit supplier info (name, phone, email, ...)
- [ ] View purchase/order history by supplier

#### 📥 Purchase
- [ ] Add purchase form (select supplier, date, invoice number)
- [ ] Add purchased items with quantity and cost
- [ ] Update product stock automatically on purchase
- [ ] View list of purchases with filters (supplier, date)
- [ ] Generate printable purchase invoice

#### ⚙️ Settings
- [x] Store settings (name, currency)
    - [ ] Add tax config to store setting


### Screenshots

#### Product list

![Product list](https://raw.githubusercontent.com/angkosal/laravel-pos/master/screenshots/products_list.png)

#### Create order

![Create order](https://raw.githubusercontent.com/angkosal/laravel-pos/master/screenshots/pos.png)

#### Order list

![Order list](https://raw.githubusercontent.com/angkosal/laravel-pos/master/screenshots/order_list.png)

#### Customer list

![Customer list](https://raw.githubusercontent.com/angkosal/laravel-pos/master/screenshots/customer_list.png)



