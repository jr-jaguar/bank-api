# Project Setup

This project requires certain setup steps to run properly. Below are the instructions for setting up and launching the project, depending on whether you have Make installed or not.

## Make Installed

If you have Make installed, follow these steps:

1. Navigate to the directory Nginx/www and run the command:
    ```
    make run
    ```

2. Add the following lines to the .env file:
    ```
    EXCHANGE_RATE_API_URL=https://minfin.com.ua/api/currency/rates/banks/
    CURRENCY_LIST_API_URL=https://minfin.com.ua/api/currency/list?type=money&locale=uk
    BANKS_LIST_API_URL=https://finance.ua/banks/api/organizationsList?locale=uk
    BANK_BRANCHES_API_URL=https://finance.ua/api/organization/v1/branches?slug=%s&locale=uk
    ```

3. Execute the following command:
    ```
    make exec
    ```

4. Run the migrations by executing:
    ```
    php artisan migrate
    ```

5. Execute the following commands to load initial data:
    ```
    php artisan currencies:load
    php artisan banks:load
    php artisan banks-branch:load
    php artisan app:update-exchange-rates
    ```

## Make Not Installed

If you do not have Make installed, follow these steps:

1. Add the following lines to the .env file:
    ```
    EXCHANGE_RATE_API_URL=https://minfin.com.ua/api/currency/rates/banks/
    CURRENCY_LIST_API_URL=https://minfin.com.ua/api/currency/list?type=money&locale=uk
    BANKS_LIST_API_URL=https://finance.ua/banks/api/organizationsList?locale=uk
    BANK_BRANCHES_API_URL=https://finance.ua/api/organization/v1/branches?slug=%s&locale=uk
    ```

2. Run the project using Docker Compose:
    ```
    docker-compose up -d
    ```

3. Execute the following command to enter the Docker container:
    ```
    docker exec -it banks-app bash
    ```

4. Inside the container, run the migrations:
    ```
    php artisan migrate
    ```

5. Run the following commands to load initial data:
    ```
    php artisan currencies:load
    php artisan banks:load
    php artisan banks-branch:load
    php artisan app:update-exchange-rates
    ```

## Accessing the Project

Once the project is set up and running, you can access it at [http://localhost:8000/](http://localhost:8000/).
