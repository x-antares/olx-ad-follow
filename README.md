## How it work
![image](https://github.com/user-attachments/assets/89ceb036-764d-4106-8332-d08b8b6ed24e)

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
