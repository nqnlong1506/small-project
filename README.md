# init-code-igniter

## Set up mysql using docker
Install docker desktop: https://docs.docker.com/engine/install/

> pull MySQL image
```
docker pull mysql:latest
```

> run container MySQL
```
docker run --name mysql-container -e MYSQL_ROOT_PASSWORD=my-secret-pw -p 3306:3306 -d mysql:latest
```

> check your running MySQL container
```
docker ps -a
```

## Run your application
```
php -S localhost:8080
```
