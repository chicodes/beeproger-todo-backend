<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://res.cloudinary.com/webzifi/image/upload/v1677091240/beeproger_Logo_gvujbd.svg" width="400"></a></p>


## Description

Develop a To-do list and/or Shopping list for us. It should consist of a loss back- and frontend, which communicate with each other.:


## Expected Features


- Shows a list of items;
- Being able to edit an item;
- A photo uploaded with the item;
- Being able to view the details of an item;
- The option to mark an item as complete;
- Being able to remove an item from the list;
- And make sure to always check for errors.



## beeproger Todo Task Documentation

### Language :

	PHP 8.2

### Framework:

	Laravel 8.8

### How to run:

#### Option 1 (Run in docker container)

If you have docker installed, bring up your terminal, navigate to the root directory of the project  and follow the steps below

  "docker-compose up -d"

  "docker exec todo_api php artisan migrate"

  baseurl should be http://localhost:1759/todo/v1


#### Option 2 (Run from IDE)

To run the project locally run the command below--

open the project in PHPSTORM and run the commands in quotes

 "composer update"
 "php -S localhost:1759 -t public"

 baseurl should be http://localhost:1759/todo/v1

### Link to Postman collection

https://drive.google.com/file/d/1sB4DmG8_9L6pudC5_zoYlA3Su9fWJ9aQ/view?usp=sharing