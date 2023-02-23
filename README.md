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



## Beeproger Todo Task backend Documentation

### Language :

	PHP 8.2

### Framework:

	Laravel 8.8

### How to run:

#### Option 1 (Run in docker container)

If you have docker installed, bring up your terminal, navigate to the root directory of the project  and follow the steps below

1. Bring up your terminal
2. Clone the project locally by running this command "git clone https://github.com/chicodes/beeproger-todo-frontend.git"
3. CD into the directory where the project was cloned.
4. Run this command "composer install"
5. Run this command "docker-compose up -d"
6. Then run this command "docker exec todo_api php artisan migrate"

Afterwards you should be able to access the app from the url below
   
http://localhost:1759/todo/v1


#### Option 2 (Run from IDE)

To run the project locally run the command below--

open the project in PHPSTORM and run the commands in quotes

 "composer update"
 "php -S localhost:1759 -t public"

 baseurl should be http://localhost:1759/todo/v1

### Link to Postman collection

https://drive.google.com/file/d/1sB4DmG8_9L6pudC5_zoYlA3Su9fWJ9aQ/view?usp=sharing

### What I would have done better if I had more time

1. I would have written more test and achieved a test coverage of at least 90%.
2. I would have used Redis or memcache for caching instead of using the file method

### PS:

This API is also accessible remotely through th link below:

https://todo-test.herokuapp.com/todo/v1

Frontend code base can be found in the github repo below:

https://github.com/chicodes/beeproger-todo-frontend