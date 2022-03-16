# CPT - CRYPTO PORTFOLIO TRACKER
CPT is a SPA that helps you keep track of your crypto portfolio and markets. 
The main features are: 
- Portfolio management (add, edit and remove cryptos).
- Data display using charts (portfolio's top cryptos, portfolio distribution).
- Market Ranks (List of cryptos).
- CPT uses the COINGECKO API to fetch the market and crypto data

You can check the live demo deployed to heroku: 
(Keep in mind that with heroku's free tier if the app receives no traffic in a 30-minute period, it will sleep, so please be patient while it "wakes up" ;) .)
https://cpptracker.herokuapp.com/login 

## Running using Docker for development: 
1 - Clone repository
2 - Install npm dependencies: 
```
sudo npm i
```
3 - Build and start docker containers: 
```
sudo docker-compose up --build
```
4 - In a separate console tab run the migrations with seed data: 
```
sudo docker-compose run --rm app php artisan migrate --seed
```
## Running locally 
1 - Clone repository
2 - Install npm dependencies
```sh 
sudo npm i
```
3 - Run migrations with seed data
```sh
sudo docker-compose run --rm app php artisan migrate --seed
```
4 - Use your favorite development environment: laragon, nginx server or xampp.