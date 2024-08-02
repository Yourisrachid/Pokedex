# Pokedex

## What is it ?

The Pokedex Project is the work of 4 web developers, collaboring to develop a pokedex applications containing informations about a thousand pokemons.

This dynamic website holds the following features :

- A homepage holding pokemon cards
- Filtering options about types, stats or name
- Pagination system
- Details page of each pokemon with its evolution cycle
- A Darkmode
- A authentification system
- An admin dashboard

We used a JSON containing all the data about pokemons to create a database with different tables.

## What did we use ?

- HMTL
- SASS / CSS
- PHP
- MySQL
- PHPMyAdmin / MySQLWorkbench
- A simple router

## The team

[Adrien B.](https://github.com/AdrienCopy)

    Installation of the router
    Authentification system ( Register / Login )
    Modifications of the database

[Isabelle](https://github.com/isab95)

    Implementation of the database and the different tables

[Jordan M.](https://github.com/MJordanBecode)

    Styling of the website
    Darkmode function
    Admin Dashboard

[Youris](https://github.com/Yourisrachid)

    Index page (+ style of the cards)
    Details page
    Pagination system
    Filtering options

## How can we see it ?

- Clone this repository

```
git clone git@github.com:Yourisrachid/Pokedex.git
```

- Go the the "Base" folder

```
cd Pokedex
cd Base
```

- Open the server

```
php -S localhost:8000
```

- Create your database

```
- Change the database information with your MySQL credentials :
    -> Pokedex/Base/assets/dbconfig.php
- Go to :
    -> localhost:8000/assets/initdb
    -> localhost:8000/assets/dbevol
```

- Open the website 

```
On your navigator : localhost:8000
```


## Links


[Pokedex JSON](https://github.com/Purukitto/pokemon-data.json)


