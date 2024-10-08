## For development of this project used dependencies:

Ensure you have installed **Docker** and **Docker Compose**

- **Docker**: Version 24.0.7 Ensure you have Docker and Docker Compose installed
- **Docker Compose**: Version 1.29.2
- **Linux**

## How it work
![image](https://github.com/user-attachments/assets/7487ad1e-8106-4bbb-8a4a-40f74685da20)


## About OLX Ad Follow

OLX Ad Follow is a Laravel-based application designed to help users track and manage their ads on OLX.


## Deployment

To deploy the application, follow these steps:

1. **Clone the Repository**
   ```bash
   git clone https://github.com/x-antares/olx-ad-follow

2. **Navigate to the Project Directory**
   ```bash
    cd olx-ad-follow

3. **Build the Docker Containers**
   ```bash
    docker-compose build

4. **Start the Docker Containers**
   ```bash
    docker-compose up -d

## Note on Using `docker-compose exec -u $(id -u):$(id -g)`

This command allows you to execute commands inside a Docker container with the permissions of the current user, which is an important aspect when working with files and accessing resources.


5. **Install Composer Dependencies**
   ```bash
    docker-compose exec -u $(id -u):$(id -g) php composer install

6. **Set Permissions for Storage**
   ```bash
    sudo chmod -R 777 storage

7. **Create the Environment File**
   ```bash
    cp .env.example .env

8. **Generate the Application Key**
   ```bash
    docker-compose exec -u $(id -u):$(id -g) php php artisan key:generate

9. **Run Database Migrations**
   ```bash
    docker-compose exec -u $(id -u):$(id -g) php php artisan migrate

10. **Seed the Database**
   ```bash
    docker-compose exec -u $(id -u):$(id -g) php php artisan db:seed
 ```
11. **Run the Queue**
   ```bash
    docker-compose exec -u $(id -u):$(id -g) php php artisan queue:work
 ```
12. **Run the Schedule**
   ```bash
    docker-compose exec -u $(id -u):$(id -g) php php artisan schedule:work

   ```
## To Run tests
   ```bash
    docker-compose exec -u $(id -u):$(id -g) php php artisan test

