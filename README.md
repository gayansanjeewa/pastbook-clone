## Features of PastBook-Clone

- Login with Facebook
- Send user's Best photos from 2019 (configurable) to there email
- Mail queue
- Necessary Docker images to spin up the env

## How to spin up PastBook-Clone
- Clone the app
```bash
git clone git@github.com:gayansanjeewa/pastbook-clone.git
```
- Spin up containers
```bash
cd pastbook-clone
docker-compose up -d
docker-compose ps
```
- Install dependencies
```bash
docker-compose exec app composer install
```
- Create `.env` from `.env.example` and generate app key.
```bash
cp .env.example .env
docker-compose exec app php artisan key:generate
```
- Fill env specific info and clear cache. Specially supply mail configurations, other once are pre-filled. 
```bash
docker-compose exec app php artisan cache:clear
```
- Run migrations
```bash
docker-compose exec app php artisan migrate
```
- Hit http://localhost:8080 and the site should be working
- Additionally, you might want to give `777` permissions to `storage/log` and `storage/framework` directories.
    
## PastBook-Clone functionaries
- Sign in with Facebook
- Check your inbox to see mail with your best photos of 2019

## Limitations PastBook-Clone

- Queue worker - Supervisord - is in development. So you have to do it manually
```bash
docker-compose exec app php artisan queue:work 
```
- Search query needs to be improved. For the moment there's no logic to determine the "Best" photos. For the moment it's a generic query. 
