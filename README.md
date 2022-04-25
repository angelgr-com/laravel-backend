# LFG web application

![PHP](https://img.shields.io/badge/php-%23777BB4.svg?logo=php&logoColor=white&style=for-the-badge) ![Laravel	](https://img.shields.io/badge/laravel-%23FF2D20.svg?logo=laravel&logoColor=white&style=for-the-badge) ![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?logo=mysql&logoColor=white&style=for-the-badge) 

Complete backend for the development of a LFG (Looking For Group) web application.

The objective is to develop with the PHP Laravel Framework a REST API (an Application Programming Interface following the design principles of the REST, or representational state transfer architectural style).

## Table of contents

  - [Application requirements](#application-requirements)
  - [Technologies](#technologies)
  - [Database model](#database-model)
  - [How to use](#how-to-use)
    - [Online](#online)
    - [Local](#local)
  - [API endpoints](#api-endpoints)

## Application requirements

- 🆗 Users must be able to register to the application, by establishing a username/password.
- ✅ Users must be able to authenticate themselves to the application by logging in
- 🆕 Users have to be able to create groups (parties) for a given videogame.
- 🔎 Users must be able to search for groups (parties) by selecting a videogame.
- 👩‍💻 Users must be able to join and leave a Party.
- 🗨 Users must be able to send messages to the Party. These messages must be able to be edited and deleted by their originating user.
- 💬 Messages existing in a Party must be displayed as a common chat.
- 📋 Users can enter and modify their profile data, e.g. their Steam user.
- 👋 Users must be able to log out of the web application.

## Technologies

Technologies used for the development of the API:

- **Laravel**. A MVC PHP framework. 
- **Laravel/Passport** for API authentication using JWT (JSON Web Token).
- **SQL**:
  - **Eloquent**, an object-relational mapper (ORM) to interact with the database.
  - **Laravel's database Query Builder**, an interface to creating and running database queries.
- **Git-Flow**, used to code by creating a branch for each feature.
- **Heroku** to upload the app to production.

## Database model

The database uses several schemas to store the application information. The data model used:

![image-20220424215550287](README.assets/image-20220424215550287.png)

## How to use

### Online

The API is deployed in Heroku so to test the endpoints it is only necessary to use a tool like [Postman](https://www.postman.com/), [Thunder](https://www.thunderclient.com/) or [Insomnia](https://insomnia.rest/).

By downloading the following files, it is possible to import the endpoints into Postman with a set of requests ready to test endpoints:

- Users (players):
  - [1_laravel-backend_players.postman_collection.json](https://raw.githubusercontent.com/angelgr-com/laravel-backend/main/assets/postman/1_laravel-backend_players.postman_collection.json)
- Games:
  - [2_laravel-backend_games.postman_collection.json](https://raw.githubusercontent.com/angelgr-com/laravel-backend/main/assets/postman/2_laravel-backend_games.postman_collection.json)
- Parties:
  - [3_laravel-backend_parties.postman_collection.json](https://raw.githubusercontent.com/angelgr-com/laravel-backend/main/assets/postman/3_laravel-backend_parties.postman_collection.json)
- Messages:
  - [4_laravel-backend_messages.postman_collection.json](https://raw.githubusercontent.com/angelgr-com/laravel-backend/main/assets/postman/4_laravel-backend_messages.postman_collection.json)

### Local

- Clone or [download](https://github.com/angelgr-com/laravel-backend/archive/refs/heads/main.zip) this repository.

- With [PHP](https://www.php.net/manual/en/install.php) and [Composer](https://getcomposer.org/download/) installed, it is possible to install [Laravel](https://laravel.com/docs/9.x/installation) by using Composer:

  ```bash
  composer global require laravel/installer
  ```

- Open the repository folder and install dependencies:

  ```
  cd laravel-backend
  composer install
  ```

- Create a new empty local schema.

- Create a .env file and enter the database configuration using environment variables:

  ```
  DB_CONNECTION=mysql
  DB_HOST=
  DB_PORT=
  DB_DATABASE=
  DB_USERNAME=
  DB_PASSWORD=
  ```

- Run database migrations and seeders:

  ```
  php artisan migrate:fresh --seed
  ```

- Start the server:

  ```
  php artisan serve
  ```

## API endpoints

- Base URL:
  - https://powerful-headland-77520.herokuapp.com/api

- Users (players):

  | method | path             | comments       |
  | ------ | ---------------- | -------------- |
  | POST   | /register        |                |
  | POST   | /login           |                |
  | POST   | /logout          | token required |
  | GET    | /profile         | token required |
  | PUT    | /profile/edit    | token required |
  | POST   | /forget          | token required |
  | POST   | /reset           | token required |
  | GET    | /reset/{pincode} | token required |

- Games:

  | method | path                | description    |
  | ------ | ------------------- | -------------- |
  | GET    | /games              | token required |
  | POST   | /games/new          | token required |
  | GET    | /games/{game_title} | token required |
  | PUT    | /games/{game_title} | token required |
  | DELETE | /games/{game_title} | token required |

- Parties:

  | method | path                         | description    |
  | ------ | ---------------------------- | -------------- |
  | GET    | /parties                     | token required |
  | POST   | /parties/new                 | token required |
  | GET    | /parties/{party_name}        | token required |
  | GET    | /parties/game/{game_title}   | token required |
  | POST   | /parties/join/{party_name}   | token required |
  | POST   | /parties/leave/{party_name}  | token required |
  | PUT    | /parties/update/{party_name} | token required |
  | DELETE | /parties/{party_name}        | token required |

- Messages:

  | method | path                         | description    |
  | ------ | ---------------------------- | -------------- |
  | GET    | /messages                    | token required |
  | POST   | /messages/new                | token required |
  | GET    | /messages/{uuid}             | token required |
  | GET    | /messages/party/{party_name} | token required |
  | PUT    | /messages/update             | token required |
  | DELETE | /messages/delete/{uuid}      | token required |