
<div align="center">
    <p align="center">
        <a href="http://nestjs.com/" target="blank"><img src="https://laravel.com/img/logotype.min.svg" width="200" alt="Nest Logo" /></a>
        <!-- <img src="https://i.ibb.co/Z1fGw5c/Brand.png" width="200" alt="Redemption Logo" /> -->
    </p>
    <h1>Assesment Result for AStudio / EAV Implementation Via Laravel</h1>
    <br />
    
    This is a Laravel Project for a Preoject management System that have EAV and Filter.
</div>


## Contents <!-- omit in toc -->

- [Installation](#installation)
- [Routes](#routes)
- [Test Case](#test)
- [License] ()

<!--lint enable awesome-list-item-->
## Installation
First make sure that you install Laravel. then Clone the project to the desired directory

```bash
git clone https://github.com/natatheleader/assesment.git
```

after cloaning the project run this command inside the folder.

```bash
Composer install 
```

This will install all the dependancies. once you dounloaded all the modules the project is now ready to start using. but to start using you need to setup your Environment configuration.

```bash
cp .env.example .env
```

this will copy and paste the .env file into your directory and then you can setup all the variables to make your project work. all the variables are disscussed in detail bellow:

```config
APP_NAME=
APP_ENV=
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

#### DATABSE_URL
Set the url of your database here. it looks someting like this:
```config
DB_CONNECTION=(Database)
DB_HOST=(Url to your DB)
DB_PORT=(The Port)
DB_DATABASE=(The name of your DB)
DB_USERNAME=(The username of your DB)
DB_PASSWORD=(The password of your DB)
```

After setting up the configuration run

```bash
php artisan key:generate
```

```bash
php artisan migrate
```

```bash
php artisan serve
```

To seed the database run

```bash
php artisan db:seed
```

## Routes

Here is the list of Routes Available for you

```bash
/*
|--------------------------------------------------------------------------
| Route List Summary
|--------------------------------------------------------------------------
|
| Authentication:
| POST   /api/auth/register
| POST   /api/auth/login
| POST   /api/logout
| GET    /api/user
|
| Users:
| GET    /api/users
| POST   /api/users
| GET    /api/users/{id}
| PUT    /api/users/{id}
| DELETE /api/users/{id}
|
| Projects:
| GET    /api/projects
| POST   /api/projects
| GET    /api/projects/{id}
| PUT    /api/projects/{id}
| DELETE /api/projects/{id}
| GET    /api/projects/{id}/users
| POST   /api/projects/{id}/users
| DELETE /api/projects/{id}/users/{user_id}
|
| Timesheets:
| GET    /api/timesheets
| POST   /api/timesheets
| GET    /api/timesheets/{id}
| PUT    /api/timesheets/{id}
| DELETE /api/timesheets/{id}
| GET    /api/projects/{id}/timesheets
| GET    /api/users/{id}/timesheets
|
| Attributes (EAV):
| GET    /api/attributes
| POST   /api/attributes
| GET    /api/attributes/{id}
| PUT    /api/attributes/{id}
| DELETE /api/attributes/{id}
| GET    /api/projects/{id}/attributes
| POST   /api/projects/{id}/attributes
```

Some Example Routs are (for EAV)

```bash
# Get all projects
GET /api/projects

# Filter by project name
GET /api/projects?filters[name]=Website Redesign

# Filter by status
GET /api/projects?filters[status]=active

# Filter by department (EAV)
GET /api/projects?filters[department]=IT

# Filter by budget greater than
GET /api/projects?filters[budget][operator]=>&filters[budget][value]=20000

# Filter by priority
GET /api/projects?filters[priority]=High

# Filter by date range
GET /api/projects?filters[start_date][operator]=>&filters[start_date][value]=2024-02-01

# Combine regular and EAV filters
GET /api/projects?filters[status]=active&filters[department]=IT

# Multiple EAV filters
GET /api/projects?filters[department]=IT&filters[priority]=High

# Complex filter combination
GET /api/projects?filters[status]=active&filters[budget][operator]=>&filters[budget][value]=30000&filters[department]=IT

# Search project names
GET /api/projects?filters[name][operator]=LIKE&filters[name][value]=Website

# Search in departments
GET /api/projects?filters[department][operator]=LIKE&filters[department][value]=Mark
```

If you seed the data you can easily access these routs

Notes:

- All URLs except Register and Login assume your using Token (Barrer) for authentication
- Remember to URL encode special characters if testing directly in the browser
- The token should be included in all requests
- All date values should be in YYYY-MM-DD format
- Valid operators are: =, >, <, LIKE (if not specified, = is used by default)

some response examples

-Login
```bash
#Input
{
    "email": "admin@example.com",
    "password": "password123"
}
#Output
{
    "user": {
        "id": 1,
        "name": "Admin",
        "email": "admin@example.com",
        "email_verified_at": null,
        "created_at": "2025-02-14T11:08:46.000000Z",
        "updated_at": "2025-02-14T11:08:46.000000Z"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNGJmMGM3ODQ0NTEyYjlkYzU2MDIyNWUwZjVkZGU5ZmI4MWJlYmI0OWVlYzUzMzFhNjg1NWM3MjU4MGY2N2Y4YzFiNTk4NmMyZWNmNDcyYjMiLCJpYXQiOjE3Mzk1MzE3ODQuNjcyNDQ5LCJuYmYiOjE3Mzk1MzE3ODQuNjcyNDUzLCJleHAiOjE3NzEwNjc3ODQuMDAxMTMsInN1YiI6IjEiLCJzY29wZXMiOltdfQ.S4wkuVIAj7V9BwwFLtlEIeEfQuV6Ztzu8rUzWE0G6vHANrXPwiZaxfghyYYVbh3NVOU09kRB1DOs20HYQD1-S-IySWDPZGqOl9Cfp9Wao0wyWQKGMrwKUAYPO-k0jTC365tF9OR-GF-Vk4pjPFUvTqs37jzqL7vYHvLm1OMSZEbLuseU-aUEa0XmgnA4VWrcgOzNkrtujAZEELO5QqKa2EijxsacCbLiCDRqY9iFZSY2qngxDRIP3ABoJAc95TA_Zyfs8OudWqdm23U7eB1M2F1QkgQ0nmIUICnpG7YOzEJLRp4CRgSH0o8cKBRKXgJ2P5diZtIO2LTDkebIkpNGiaaNg722NJki6hVruBzGcYZAccciUSXIBIldTbfWaeUzLhGAKH_ML6rds8FEXtpHCHzZicLFoAMwlnN3piUI6C2r4iQBnEcBE1OuNWnT7AenSvSxLuEAE26XBdu1cYBCwW23Mh3BT-_uIj9xjGcRAZMWK28LMIxHHcxJDwJNmFYOQCn2g0-4Ut_awsyLN3avkvh9Z7tjA5evnzIxaUyJhWyc2iI5WPrPKBiXeAsxlbadZJkfBX_jIgiY-HAic5FI-41TNaYzyYbFbwL9mTEyGozribVIbhghqqHNNyZbPPNUehhqzQMeHVeJ2F1SIobCWUZ0L8Xo8denHlxxPjJRMqc"
}
```

-Others
```bash
#Link
Get http://localhost:8000/api/users
#Output
{
    "data": [
        {
            "id": 1,
            "name": "Admin",
            "email": "admin@example.com",
            "email_verified_at": null,
            "created_at": "2025-02-14T11:08:46.000000Z",
            "updated_at": "2025-02-14T11:08:46.000000Z"
        },
        {
            "id": 2,
            "name": "John Doe",
            "email": "john@example.com",
            "email_verified_at": null,
            "created_at": "2025-02-14T11:08:47.000000Z",
            "updated_at": "2025-02-14T11:08:47.000000Z"
        },
        {
            "id": 3,
            "name": "Jane Smith",
            "email": "jane@example.com",
            "email_verified_at": null,
            "created_at": "2025-02-14T11:08:47.000000Z",
            "updated_at": "2025-02-14T11:08:47.000000Z"
        }
    ],
    "links": {
        "first": "http://localhost:8000/api/users?page=1",
        "last": "http://localhost:8000/api/users?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/users?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://localhost:8000/api/users",
        "per_page": 10,
        "to": 3,
        "total": 3
    }
}

#Link
GET http://localhost:8000/api/projects

#Output
{
    "data": [
        {
            "id": 1,
            "name": "Website Redesign",
            "status": "active",
            "created_at": "2025-02-14T11:08:47.000000Z",
            "updated_at": "2025-02-14T11:08:47.000000Z",
            "attribute_values": [
                {
                    "id": 1,
                    "attribute_id": 1,
                    "entity_id": 1,
                    "value": "IT",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 1,
                        "name": "department",
                        "type": "text",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 2,
                    "attribute_id": 2,
                    "entity_id": 1,
                    "value": "2024-02-01",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 2,
                        "name": "start_date",
                        "type": "date",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 3,
                    "attribute_id": 3,
                    "entity_id": 1,
                    "value": "2024-05-01",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 3,
                        "name": "end_date",
                        "type": "date",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 4,
                    "attribute_id": 4,
                    "entity_id": 1,
                    "value": "50000",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 4,
                        "name": "budget",
                        "type": "number",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 5,
                    "attribute_id": 5,
                    "entity_id": 1,
                    "value": "High",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 5,
                        "name": "priority",
                        "type": "select",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                }
            ]
        },
        {
            "id": 2,
            "name": "Marketing Campaign",
            "status": "pending",
            "created_at": "2025-02-14T11:08:47.000000Z",
            "updated_at": "2025-02-14T11:08:47.000000Z",
            "attribute_values": [
                {
                    "id": 6,
                    "attribute_id": 1,
                    "entity_id": 2,
                    "value": "Marketing",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 1,
                        "name": "department",
                        "type": "text",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 7,
                    "attribute_id": 2,
                    "entity_id": 2,
                    "value": "2024-03-01",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 2,
                        "name": "start_date",
                        "type": "date",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 8,
                    "attribute_id": 3,
                    "entity_id": 2,
                    "value": "2024-06-01",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 3,
                        "name": "end_date",
                        "type": "date",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 9,
                    "attribute_id": 4,
                    "entity_id": 2,
                    "value": "30000",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 4,
                        "name": "budget",
                        "type": "number",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 10,
                    "attribute_id": 5,
                    "entity_id": 2,
                    "value": "Medium",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 5,
                        "name": "priority",
                        "type": "select",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                }
            ]
        },
        {
            "id": 3,
            "name": "Sales Training",
            "status": "completed",
            "created_at": "2025-02-14T11:08:47.000000Z",
            "updated_at": "2025-02-14T11:08:47.000000Z",
            "attribute_values": [
                {
                    "id": 11,
                    "attribute_id": 1,
                    "entity_id": 3,
                    "value": "Sales",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 1,
                        "name": "department",
                        "type": "text",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 12,
                    "attribute_id": 2,
                    "entity_id": 3,
                    "value": "2024-01-01",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 2,
                        "name": "start_date",
                        "type": "date",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 13,
                    "attribute_id": 3,
                    "entity_id": 3,
                    "value": "2024-01-31",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 3,
                        "name": "end_date",
                        "type": "date",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 14,
                    "attribute_id": 4,
                    "entity_id": 3,
                    "value": "15000",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 4,
                        "name": "budget",
                        "type": "number",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 15,
                    "attribute_id": 5,
                    "entity_id": 3,
                    "value": "Low",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 5,
                        "name": "priority",
                        "type": "select",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                }
            ]
        }
    ],
    "links": {
        "first": "http://localhost:8000/api/projects?page=1",
        "last": "http://localhost:8000/api/projects?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/projects?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://localhost:8000/api/projects",
        "per_page": 10,
        "to": 3,
        "total": 3
    }
}

#Link
GET localhost:8000/api/projects?filters[name]=Website Redesign

#output
{
    "data": [
        {
            "id": 1,
            "name": "Website Redesign",
            "status": "active",
            "created_at": "2025-02-14T11:08:47.000000Z",
            "updated_at": "2025-02-14T11:08:47.000000Z",
            "attribute_values": [
                {
                    "id": 1,
                    "attribute_id": 1,
                    "entity_id": 1,
                    "value": "IT",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 1,
                        "name": "department",
                        "type": "text",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 2,
                    "attribute_id": 2,
                    "entity_id": 1,
                    "value": "2024-02-01",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 2,
                        "name": "start_date",
                        "type": "date",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 3,
                    "attribute_id": 3,
                    "entity_id": 1,
                    "value": "2024-05-01",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 3,
                        "name": "end_date",
                        "type": "date",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 4,
                    "attribute_id": 4,
                    "entity_id": 1,
                    "value": "50000",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 4,
                        "name": "budget",
                        "type": "number",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                },
                {
                    "id": 5,
                    "attribute_id": 5,
                    "entity_id": 1,
                    "value": "High",
                    "created_at": "2025-02-14T11:08:47.000000Z",
                    "updated_at": "2025-02-14T11:08:47.000000Z",
                    "attribute": {
                        "id": 5,
                        "name": "priority",
                        "type": "select",
                        "created_at": "2025-02-14T11:08:47.000000Z",
                        "updated_at": "2025-02-14T11:08:47.000000Z"
                    }
                }
            ]
        }
    ],
    "links": {
        "first": "http://localhost:8000/api/projects?page=1",
        "last": "http://localhost:8000/api/projects?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/projects?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://localhost:8000/api/projects",
        "per_page": 10,
        "to": 1,
        "total": 1
    }
}
```

These are just some Examples to show the structure of the response. Your results follow the same structure.

## Test

To run tests first

```bash
cp .env .env.testing
```

this will create a new environment file so you can use a test database which is different from your main Database

After that configure your new environment file and run

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter ProjectTest

# Run specific test method
php artisan test --filter ProjectTest::it_can_filter_projects_by_eav_attributes
```

## License

[MIT](LICENSE)