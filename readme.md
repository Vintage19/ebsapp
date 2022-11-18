# Backend Blog API Laravel

A example of RESTful API in Laravel Framework 5.5.50, created as a test task.

I used Database **mysql**.

## Public Endpoints

**Get all posts:** `GET /api/articles`

**Get a single post:** `GET /api/articles/{id}`

**Get first 5 categories sorted by voted ratings:** `POST /api/topcategories`


**Register new user:** `POST /api/register`

**Login user:** `POST /api/login`

**Logout user:** `POST /api/logout`

## Private Endpoints (for registered users)

**Get all my articles:** `GET /api/myarticles`

**Add new articles:** `POST /api/addarticle`

**Edit article:** `PUT /api/editarticle/{id}`

**Delete article:** `POST /api/deletearticle/{id}`

**Vote article (up or down):** `POST /api/vote`

## Config API

When you have the .env with your database connection set up you can run your migrations

```bash
php artisan migrate
```
Then run `php artisan db:seed` to fill the database with categories.

List of categories need to add in array in database\seeds\CategoriesTableSeeder.php, where first attribute is name of category, but second attribute is url of category.

```
 // array of existing categories
       $category_arr = array('Category 1'=>'category_1',
                            'Category 2'=>'category_2',
                            'Category 3'=>'category_3',
                            'Category 4'=>'category_4',
                            'Category 5'=>'category_5',);
```

## Register new user and api-key

After registration 

```
$ curl -X POST http://localhost:8000/api/register \
 -H "Accept: application/json" \
 -H "Content-Type: application/json" \
 -d '{"name": "User", "email": "test1@test.com", "password": "123456", "password_confirmation": "123456"}'

```
 you will get the api-key

 ```
 {
    "data": {
        "name": "User",
        "email": "test1@test.com",
        "updated_at": "2022-11-18 09:43:15",
        "created_at": "2022-11-18 09:43:15",
        "id": 2,
        "api_token": "NKqerNMB8307CQZJqfsGXBF28IkWJnjAMCMRCXMOT7BC1UzHBHTWLxOavAkN"
    }
}
 ```

## Auth in api

You can use your api key in header to auth in api

```
Authorization: Bearer NKqerNMB8307CQZJqfsGXBF28IkWJnjAMCMRCXMOT7BC1UzHBHTWLxOavAkN

```

## Add an article (for auth user)

All articles are created with reference to the current user.
Params name, body and category are required

```
$ curl -X POST http://localhost:8000/api/register \
 -H "Accept: application/json" \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer NKqerNMB8307CQZJqfsGXBF28IkWJnjAMCMRCXMOT7BC1UzHBHTWLxOavAkN" \
 -d '{"title": "Name of article", "body": "Body text of article", "category": "1"}'

```

Response Example

```
{
    "title": "Name of article",
    "body": "Body text of article",
    "user_id": 2,
    "updated_at": "2022-11-18 10:55:37",
    "created_at": "2022-11-18 10:55:37",
    "id": 12,
    "category": "2"
}
```

## Edit an article (for auth user)

Each user can edit only their own articles.
Params name, body and category are required.

```
$ curl -X PUT http://localhost:8000/api/editarticle/{id} \
 -H "Accept: application/json" \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer NKqerNMB8307CQZJqfsGXBF28IkWJnjAMCMRCXMOT7BC1UzHBHTWLxOavAkN" \
 -d '{"title": "Name of article", "body": "Body text of article", "category": "2"}'

```

Response Example

```
[
    {
        "id": 10,
        "title": "Name of article",
        "body": "Body text of article",
        "created_at": "2022-11-18 09:57:47",
        "updated_at": "2022-11-18 09:57:47",
        "user_id": 2,
        "vote": null,
        "category_id": 2,
        "category_name": "Category 2"
    }
]
```

## Show my articles (for auth user)

In this method, each user can get all their own articles

```
$ curl -X GET http://localhost:8000/api/myarticles \
 -H "Accept: application/json" \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer NKqerNMB8307CQZJqfsGXBF28IkWJnjAMCMRCXMOT7BC1UzHBHTWLxOavAkN" \

```

Response Example

```
[
    {
        "id": 10,
        "title": "Name of article",
        "body": "Body text of article",
        "created_at": "2022-11-18 09:57:47",
        "updated_at": "2022-11-18 09:57:47",
        "user_id": 2,
        "vote": null,
        "category_id": 2,
        "category_name": "Category 2"
    },
    {
        "id": 11,
        "title": "Name of article",
        "body": "Body text of article",
        "created_at": "2022-11-18 09:58:11",
        "updated_at": "2022-11-18 09:58:11",
        "user_id": 2,
        "vote": null,
        "category_id": 2,
        "category_name": "Category 2"
    }
]
```


## Vote (for auth user)

In this method, each user can vote for each article once.
Method can get bool param vote (0 - vote down) or (1 - vote up), and article_id param. All params are required

```
$ curl -X GET http://localhost:8000/api/vote \
 -H "Accept: application/json" \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer NKqerNMB8307CQZJqfsGXBF28IkWJnjAMCMRCXMOT7BC1UzHBHTWLxOavAkN" \
  -d '{"article_id": 2, "vote": 1}'

```

## Delete article (for auth user)

In this method, each user delete articles.

```
$ curl -X DELETE http://localhost:8000/api/deletearticle/{id} \
 -H "Accept: application/json" \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer NKqerNMB8307CQZJqfsGXBF28IkWJnjAMCMRCXMOT7BC1UzHBHTWLxOavAkN" \

```

## Show all articles (for public user)

In this method, each user can view all articles with pagination.
Users can also filter articles by keyword (using the query get parameter) or by category id (using the cat_id get parameter)

```
$ curl -X GET http://localhost:8000/api/articles \
 -H "Accept: application/json" \
 -H "Content-Type: application/json" \

```

Response Example

```
{
    "current_page": 1,
    "data": [
        {
            "id": 11,
            "title": "Name of article",
            "body": "Body text of article",
            "created_at": "2022-11-18 09:58:11",
            "updated_at": "2022-11-18 09:58:11",
            "user_id": 2,
            "vote": null,
            "category_id": 2,
            "category_name": "Category 2"
        },
        {
            "id": 10,
            "title": "Name of article",
            "body": "Body text of article",
            "created_at": "2022-11-18 09:57:47",
            "updated_at": "2022-11-18 09:57:47",
            "user_id": 2,
            "vote": null,
            "category_id": 2,
            "category_name": "Category 2"
        }
    ],
    "first_page_url": "http://127.0.0.1:8000/api/articles?page=1",
    "from": 1,
    "last_page": 5,
    "last_page_url": "http://127.0.0.1:8000/api/articles?page=5",
    "next_page_url": "http://127.0.0.1:8000/api/articles?page=2",
    "path": "http://127.0.0.1:8000/api/articles",
    "per_page": 2,
    "prev_page_url": null,
    "to": 2,
    "total": 10
}

```

Filter with query

```
$ curl -X GET http://localhost:8000/api/articles?query={query} \
 -H "Accept: application/json" \
 -H "Content-Type: application/json" \

```

Response Example

```
{
    "current_page": 1,
    "data": [
        {
            "id": 7,
            "title": "test 25",
            "body": "bbb 25",
            "created_at": "2022-11-17 14:09:58",
            "updated_at": "2022-11-17 14:09:58",
            "user_id": 1,
            "vote": 1,
            "category_id": 2,
            "category_name": "Category 2"
        },
        {
            "id": 8,
            "title": "test 25",
            "body": "bbb 25",
            "created_at": "2022-11-17 14:09:58",
            "updated_at": "2022-11-17 14:09:58",
            "user_id": 1,
            "vote": 0,
            "category_id": 1,
            "category_name": "Category 1"
        }
    ],
    "first_page_url": "http://127.0.0.1:8000/api/articles?page=1",
    "from": 1,
    "last_page": 3,
    "last_page_url": "http://127.0.0.1:8000/api/articles?page=3",
    "next_page_url": "http://127.0.0.1:8000/api/articles?page=2",
    "path": "http://127.0.0.1:8000/api/articles",
    "per_page": 2,
    "prev_page_url": null,
    "to": 2,
    "total": 5
}
```

Filter with category id

```
$ curl -X GET http://localhost:8000/api/articles?cat_id={id} \
 -H "Accept: application/json" \
 -H "Content-Type: application/json" \

```

Response Example

```
{
    "current_page": 1,
    "data": [
        {
            "id": 8,
            "title": "test 25",
            "body": "bbb 25",
            "created_at": "2022-11-17 14:09:58",
            "updated_at": "2022-11-17 14:09:58",
            "user_id": 1,
            "vote": 0,
            "category_id": 1,
            "category_name": "Category 1"
        },
        {
            "id": 6,
            "title": "test 1",
            "body": "test 2",
            "created_at": "2022-11-16 17:12:58",
            "updated_at": "2022-11-16 17:12:58",
            "user_id": 0,
            "vote": -1,
            "category_id": 1,
            "category_name": "Category 1"
        }
    ],
    "first_page_url": "http://127.0.0.1:8000/api/articles?page=1",
    "from": 1,
    "last_page": 2,
    "last_page_url": "http://127.0.0.1:8000/api/articles?page=2",
    "next_page_url": "http://127.0.0.1:8000/api/articles?page=2",
    "path": "http://127.0.0.1:8000/api/articles",
    "per_page": 2,
    "prev_page_url": null,
    "to": 2,
    "total": 3
}

```


## Show article (for public user)

In this method, each user can view an article.

```
$ curl -X GET http://localhost:8000/api/articles/{id} \
 -H "Accept: application/json" \
 -H "Content-Type: application/json" \

```

Response Example

```
[
    {
        "id": 10,
        "title": "Name of article",
        "body": "Body text of article",
        "created_at": "2022-11-18 09:57:47",
        "updated_at": "2022-11-18 09:57:47",
        "user_id": 2,
        "vote": null,
        "category_id": 2,
        "category_name": "Category 2"
    }
]

```

## Top categories (for public user)

In this method, each user can view a top 5 categories (which have at least 2 articles) with count of articles and rate of votes.

```
$ curl -X GET http://localhost:8000/api/topcategories/ \
 -H "Accept: application/json" \
 -H "Content-Type: application/json" \

```

Response Example

```
[
    {
        "id": 2,
        "name": "Category 2",
        "url": "category_2",
        "article_count": 6,
        "votes": "3"
    },
    {
        "id": 1,
        "name": "Category 1",
        "url": "category_1",
        "article_count": 4,
        "votes": "-1"
    }
]

```