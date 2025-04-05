# FINISHED

# Instructions:
1. Make sure you have up-to-date versions of PHP, Composer, Node.js, PostgreSQL installed;
2. Copy the repository: `git clone https://github.com/TimurDev-hub/tasker.git`;
3. Install PHP dependencies: `composer install`;
4. Install Node.js dependencies: `npm install`;
5. Create a `.env` file in the root of your project with the following contents:
	`DB_HOST=YOUR_LOCALHOST`
	`DB_NAME=YOUR_DB_NAME`
	`DB_USER=YOUR_USERNAME`
	`DB_PASSWORD=YOUR_PASSWORD`
6. Create new PostgreSQL database;
7. Create table **tasks**:
	```sql
	CREATE TABLE tasks (
	task_id SERIAL PRIMARY KEY,
	user_id INT NOT NULL,
	task_title VARCHAR(128) NOT NULL,
	task_text VARCHAR(128) NOT NULL
	);
	```
8. Create table **users**:
	```sql
	CREATE TABLE users (
	user_id SERIAL PRIMARY KEY,
	user_name VARCHAR(128) NOT NULL UNIQUE,
	user_password VARCHAR(128) NOT NULL
	);
	```
9. Configure your web server appropriately to run the application.