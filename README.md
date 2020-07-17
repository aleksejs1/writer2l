# Writer2l (Writer tool)

![Showcase](https://writer2l.com/screen_2020_07_17.png)

A platform for writing novels. Details for Writer2l can be found on the official website at https://writer2l.com/.

## Installation
### Manual
``` bash
# Create .env file
cp .env.dist .env

# Configure your env
nano .env

# Install deps
composer install

# Run migrations
php bin/console doctrine:migrations:migrate

# Create user [command] [new user username] [new user password]
php bin/console w2l:user:create admin qwerty

# Build assets
yarn install
yarn run dev
```
### Docker
``` bash
docker-compose up
```
## Tech spec
Writer2l is written in PHP with Symfony 5 framework, MySQL database, webpack.

## License

The Writer2l source is provided under the MIT License. The libraries used by, and included with, Writer2l are provided under their own licenses.

## Inspiration

Creation of Writer2l was inspired by [yWrite](http://www.spacejock.com/yWriter6.html) and [BookStack](https://www.bookstackapp.com/).
