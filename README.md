# Tournament Game
===============

## Description ##

This symfony project represents a tournament game.

User can choose character based on of three different races: 

* Dwarves (attack power: 8, defense power: 2)
* Elves (attack power: 7, defense power: 3)
* Hobbits (attack power: 6, defense power: 4)

Every one of them have different skill set: attack coefficient and defense coefficient.

As soon as player win fights s/he gets points:

* 1 point if other party weaker then s/he
* 2 point if other party has same strength
* 3 point if other party stronger then s/he
 
When your points reach certain level your character will be promoted from Soldier to Sergeant and then to Lieutenant.
Depending on your rank your game will have different number of moves.

Fight is being hold following way: You will choose how many moves you'd be attacking and how many defending. 
Same will do your party. But fight also has a weather condition which will affect result of the game.  
When it's bad weather attack can take more power then defense.  
Weather condition will be set randomly for every fight, so you don't know which strategy will bring a victory.

## Deployment ##

Please clone application to your computer. Create two databases for the application: production and test.
Then copy **app/config/parameters.yml.dist** to the same folder and put production database settings.
After which please edit **app/config/config_test.yml** with test database settings.

Then run open terminal move to the application directory and run following commands:


```

#! composer install

```

```

#! app/console doctrine:schema:create

```
Now you can start consuming API. 

For running unit test make sure you have installed phpunit (6.1) and run **bin/run_tests.sh** from application root folder.

## API ##

We have following end-points in our API. Note that some of the end-points require token based authentication. 
Please make sure you're passing HTTP_token header with the header you will get after registering new player.

Open end-points are following:

* get available races                               GET     /api/races                        
* leaderboard (rank and page could be skipped)      GET     /api/leaderboard/{rank}/{page}     
* new player registration                           POST    /api/register

Those are token based:

* start new game                                    POST   /api/games                        
* available games for current user                  GET    /api/games/available              
* join to existing game                             POST   /api/games/{id}/join              
* fight the game                                    POST   /api/games/{id}/fight             
* get own player info                               GET    /api/player