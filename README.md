Install guide
========================
1) clone the repository

- git clone git@bitbucket.org:cyrusnimda/triptest.git


2) Update vendors (You must have composer and bower installed )

  - composer install
  - bower install


3) Create database (You must have mysql-server running)

  - Check user and password in app/config/parameters.yml
  - php app/console doctrine:database:create
  - php app/console doctrine:schema:update --force

4) Create first customer, email=triptest@ontro.co.uk, password=triptest

  - php app/console triptest:init


5) Start the develop server

  - php app/console server:start


6) Enjoy !

  - http://localhost:8000/
