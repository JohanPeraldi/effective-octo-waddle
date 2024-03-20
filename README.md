# Backend Project

This back-end project is built with PHP, utilizing the `vlucas/phpdotenv` package for environment variable management.

## Prerequisites

- PHP 7.4 or higher
- Composer for managing PHP dependencies

## Project Setup

Clone the repository and install PHP dependencies:

```sh
composer install
```

### Running the server

In development, I used an Apache server and MySQL database on [XAMPP](https://www.apachefriends.org/) to run the server. Depending on your settings, it might run on a different port, but typically something like `http://localhost:8000`.


### Environment Configuration

Create `.env.development` and `.env.production` files in the root directory for development and production environments, respectively. After setting up your database, you will define your environment variables, such as `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` in these files (see `.env.example`).

## Database Setup

Navigate to the `setup` directory and run the `database_setup.php` script to set up your database. This will create the database and the four tables needed for this project. Six demo products will also be insterted in the database. These can then be deleted directly in the app, and other products added.
