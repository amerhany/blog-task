# Blog Task API

## 📑 Introduction

Blog Task is a simple blog application built with Laravel. This API allows users to manage posts and comments, including creating, updating, and retrieving data.

---

## 🛠️ Prerequisites

Make sure you have the following installed:

* PHP 8.x
* Composer
* MySQL
* Git

---

## 🚀 Installation Steps

1. **Clone the Repository**

```bash
git clone https://github.com/amerhany/blog-task.git
cd blog-task
```

2. **Install Dependencies**

```bash
composer install
```

3. **Environment Configuration**

* Copy the example environment file and modify it:

```bash
cp .env.example .env
```

* Generate the application key:

```bash
php artisan key:generate
```

* Update the following in the `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_task
DB_USERNAME=root
DB_PASSWORD=your_password
```

4. **Database Setup**

```bash
php artisan migrate
```

* Seed the database with an admin user:

```bash
php artisan db:seed --class=AdminUserSeeder
```

---

## 🌐 Running the Server

```bash
php artisan serve
```

* Access the API at: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 📝 Running Tests

```bash
php artisan test
```

---

## 🧑‍💻 API Endpoints

### Auth

* **Register:** POST `/api/auth/register`
* **Login:** POST `/api/auth/login`
* **Logout:** POST `/api/auth/logout` (requires authentication)

### Posts

* **List all posts:** GET `/api/posts`
* **Search posts:** GET `/api/posts/search?query=title&category=1&start_date=2023-01-01&end_date=2023-12-31&author=5`
* **Show specific post:** GET `/api/posts/{id}`
* **Create post:** POST `/api/posts` (requires authentication)
* **Update post:** PUT `/api/posts/{id}` (requires authentication)
* **Delete post:** DELETE `/api/posts/{id}` (requires authentication)

### Comments

* **Add comment:** POST `/api/posts/{postId}/comments` (requires authentication)
* **Update comment:** PUT `/api/posts/{postId}/comments/{id}` (requires authentication)
* **Delete comment:** DELETE `/api/posts/{postId}/comments/{id}` (requires authentication)

## Testing the API

You can test the API using Postman or any other API testing tool. Make sure to include the `Authorization: Bearer {token}` header for authenticated routes.

---

## 🪲 Troubleshooting

* If migrations fail, make sure the database credentials in the `.env` file are correct.
* Check if your database server is running.

Feel free to open an issue if you encounter any problems!
