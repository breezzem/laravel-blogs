a# Laravel Technical Test 🚀

Hey there! 👋

This is a technical assessment project we use to evaluate Laravel candidates. Don't worry, it's not meant to trick you or anything - we just want to see how you approach building features in Laravel.

## What's This About?

You'll be working with a simple blog management system. The project already has a bunch of tests written, and your job is to make them all pass. That's it! 

The tests will guide you through what needs to be built - think of them as your requirements document. Read through them, understand what they're asking for, and implement the features accordingly.

## Getting Started

First things first, get the project up and running:

### Install php
https://laravel.com/docs/12.x/installation#installing-php

```bash
# Install dependencies
composer install
npm install

# Set up your environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Build assets (if needed)
npm run build
```

## Your Mission

Run the test suite and see what's failing:

```bash
php artisan test
```

Then, one by one, make those tests green! 🟢

The tests cover a bunch of Laravel concepts you'll probably use in real projects:
- CRUD operations
- Validation
- Policies & authorization
- Soft deletes
- Middleware
- Events & listeners
- Mailables
- Model relationships
- Factories
- Mutators & accessors
- Notifications
- Jobs & queues
- Artisan commands
- Package integration

## Tips

- Read the test files! They have comments explaining what each test is checking
- Don't overthink it - the tests are pretty straightforward
- Feel free to check Laravel docs if you need a refresher on anything
- The tests will tell you exactly what's expected, so follow them closely

## When You're Done

Once all tests are passing, you're good to go! We'll review your implementation and see how you approached the problem.

Good luck! 🍀
