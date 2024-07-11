

# Simple Dashboard

This project is designed to manage projects with CRUD operations and user registration featuring email verification using Laravel. It includes functionality for uploading multiple files per project. 
I have configured one email and stored the application password in the .env file for convenience.

## Prerequisites
Before using the email verification feature, ensure you have the following:

1. Gmail Account with 2-Step Verification

- Activate 2-Step Verification for your Gmail account.
- Create an application password for your Laravel application.
  
2. Setup .env File

- Add the generated application password to your .env file under the MAIL_PASSWORD or similar environment variable.

## Features
- User Registration with Email Verification

  - Allows users to register with their name, email, and password.
  - Sends a verification email to the registered email address with a unique token.
- File Upload for Projects

  - Provides a form to upload multiple files for a project.
  - Validates file types and sizes before uploading.
  - Stores uploaded files on the server and records metadata in the database.
    
## Limitations
1. Verification Testing
  - Currently, verification is tested only with Gmail accounts.
  - Ensure your Gmail account settings are configured as mentioned in the Prerequisites section.

## Usage
1. Clone the repository:

  ```bash
  git clone https://github.com/RamadhanIY/simple-dashboard/
  cd laravel
```

2. Install dependencies:

```bash
composer install
```

3. Configure your .env file:

```bash
cp .env.example .env
```
- Add your Gmail credentials and application password to the .env file:
  ```dotenv
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.googlemail.com
  MAIL_PORT=465
  MAIL_USERNAME=your-email@gmail.com
  MAIL_PASSWORD=your-application-password
  MAIL_ENCRYPTION=ssl
  ```
Set up your database credentials, mail configuration (using Gmail with 2-Step Verification), and any other necessary environment variables.

4. Generate application key:

```bash
php artisan key:generate
```
5. Run database migrations:

```bash
php artisan migrate
```
6. Serve the application:

```bash
php artisan serve
```
Access the application in your web browser at http://localhost:8000.
