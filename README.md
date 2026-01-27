# PHP_Laravel12_Service_Container_Dependency_Injection

A complete Laravel 12 demo project that shows **how to integrate LinkedIn share buttons** into a Laravel application using Blade, TailwindCSS, and JavaScript. The project demonstrates **three different LinkedIn sharing methods** using a simple blog-style setup.

---

## Project Overview

This project helps developers understand how to:

* Create a Laravel project from scratch
* Build routes, controllers, and Blade views
* Display a list of blog posts and single post pages
* Integrate LinkedIn sharing functionality
* Use TailwindCSS for clean and responsive UI

The project is beginner-friendly and ideal for learning social sharing integration in Laravel.

---

## Features

* Laravel 12 project structure
* Simple blog post listing
* Single post detail page
* Three LinkedIn share button implementations
* TailwindCSS-based responsive UI
* Clean Blade templates
* No database required (array-based demo data)

---

## Tech Stack

* Backend: Laravel 12, PHP 8+
* Frontend: Blade, JavaScript, TailwindCSS
* Sharing Platform: LinkedIn

---

## Installation & Setup

### Step 1: Create a New Laravel Project

```bash
composer create-project laravel/laravel laravel-linkedin-share
cd laravel-linkedin-share
```

Create a controller:

```bash
php artisan make:controller PostController
```

---

## Step 2: Set Up Routes

Edit `routes/web.php`:

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
```

---

## Step 3: Create Controller

File: `app/Http/Controllers/PostController.php`

The controller:

* Stores demo blog posts in arrays
* Displays all posts on the index page
* Displays a single post based on ID
* Handles invalid post IDs with 404 error

---

## Step 4: Create Views

### Posts List Page

Path:

```
resources/views/posts/index.blade.php
```

Responsibilities:

* Display list of blog posts
* Show title, short content, author
* Link to single post page

---

### Single Post Page

Path:

```
resources/views/posts/show.blade.php
```

Responsibilities:

* Display full post content
* Show author and publish date
* Display featured image
* Provide LinkedIn share buttons

LinkedIn Share Methods:

1. Simple URL share link
2. Official LinkedIn SDK share button
3. Custom JavaScript share popup

---

## Step 5: Update Welcome Page

Path:

```
resources/views/welcome.blade.php
```

Purpose:

* Acts as landing page
* Explains project purpose
* Provides navigation to demo blog
* Explains how LinkedIn sharing works

---

## Step 6: App Name Configuration (Optional)

Edit `config/app.php`:

```php
'name' => env('APP_NAME', 'Laravel Blog'),
```

This name is used when sharing content.

---

## Step 7: Run the Application

Start the development server:

```bash
php artisan serve
```

Application URL:

```
http://localhost:8000
```
## Screenshot

<img width="1891" height="280" alt="image" src="https://github.com/user-attachments/assets/e5bb9abc-d11c-4a57-9322-217d23f39575" />


---

## How LinkedIn Sharing Works

### Method 1: Simple URL Sharing

* Uses LinkedIn share URL
* Opens LinkedIn share dialog in new tab
* No JavaScript required

### Method 2: LinkedIn SDK

* Uses LinkedIn official script
* Supports share counters
* Requires LinkedIn JavaScript SDK

### Method 3: Custom JavaScript Sharing

* Full control over popup window
* Custom tracking and logging
* Ideal for advanced customization

---

## Usage Flow

1. Open home page
2. Click "Launch Demo Blog"
3. View list of posts
4. Open a single post
5. Use any LinkedIn share method

---

## Project Structure

```
laravel-linkedin-share/
├── app/Http/Controllers
│   └── PostController.php
├── resources/views
│   ├── welcome.blade.php
│   └── posts
│       ├── index.blade.php
│       └── show.blade.php
├── routes
│   └── web.php
└── config
    └── app.php
```

---

## Learning Outcomes

* Understand Laravel routing and controllers
* Learn Blade templating basics
* Implement social sharing features
* Use TailwindCSS in Laravel
* Integrate third-party platforms

---

## Use Cases

* Laravel beginners
* MCA / BCA projects
* Interview demonstrations
* Blog or content sharing systems

---

## License

This project is open-source and available under the MIT License.

---

## Final Note

This project is designed for learning and demonstration purposes. You can easily extend it by adding database support, authentication, or dynamic content.

If this project helps you, consider starring the repository on GitHub.

