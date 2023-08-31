# Laravel 10 API

## Using Laravel 10 and PostgreSQL

Based on tutorial at :
https://santrikoding.com/tutorial-set/tutorial-restful-api-laravel-10

#### run laravel

```
php artisan serve
```

server will run on http://localhost:8000/

#### migrate database

```
php artisan serve
```

#### get all posts

```http
GET /api/posts/
```

#### get a post

```http
GET /api/posts/:id
```

#### create post

```http
POST /api/posts/

Content-Type : multipart/form-data
name = "title" Laravel 10 REST API 01 Install Laravel 10
name = "content" Lets install Laravel 10 first
name = "image" Content-Type : image/png filename = "laravel10.png"
```
