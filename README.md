<h1 align="center">
  <a href="#user-content-------votix--"><img src="https://raw.githubusercontent.com/ClubNix/Votix/master/public/logovotix.jpg" alt="Logo Votix" width="150" height="150"></a>
  <br>
  Votix by Club*Nix
  <br>
</h1>

<p align="center">
  <a href="https://www.clubnix.fr/" alt="Club*Nix"><img src="https://img.shields.io/badge/A%20project%20-Club%2ANix-7ef80b.svg" /></a>
  <a href="https://travis-ci.org/ClubNix/Votix" alt="Build Status"><img src="https://travis-ci.org/ClubNix/Votix.svg?branch=master" /></a>
  <a href="https://github.com/ClubNix/Votix/blob/master/LICENCE" alt="MIT"><img src="https://img.shields.io/github/license/ClubNix/Votix.svg" /></a>
</p>

<p align="center">
  <a href="https://secure.php.net/manual/en/intro-whatis.php" alt="PHP 7.3"><img src="https://img.shields.io/badge/PHP-^7.3-787cb4.svg" /></a>
  <a href="https://symfony.com/what-is-symfony" alt="Symfony 5.2"><img src="https://img.shields.io/badge/Symfony-5.2-7aba20.svg" /></a>
</p>

<p align="center"><b>Votix is an advanced and secure online voting platform.</b></p>

## Quickstart

```bash
# clone latest version
git clone git@github.com:ClubNix/Votix.git
cd Votix
# install dependencies for development and testing
docker-compose run --rm votix make install_dev
# run votix container in background
docker-compose up -d --force-recreate
# Votix should be available from now on http://localhost:8000
# run test suites
docker-compose exec votix make test
```

## Documentation

Please check Votix Wiki for installation and configuration instructions for production
https://github.com/ClubNix/Votix/wiki

## Contributing

```bash
# run a command inside running container
docker-compose exec votix [command]
# run a command inside a temporary votix container
docker-compose run --rm votix [command]

# run tests 
docker-compose exec votix make test
# run tests with code coverage (tests/_output/coverage)
docker-compose exec votix make test_coverage
# rebuild assets
docker-compose exec votix yarn build
# reset database
docker-compose exec votix make reset
# updating container
docker-compose down
docker-compose up --force-recreate

# exec bash in container / bash root in container
make inside
make inside_root

# composer
docker-compose exec votix composer
# yarn
docker-compose exec votix yarn
```

## License

Votix is released under the MIT license.