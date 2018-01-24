Install
=======

* enable hyper-V in BIOS
* setup proxy ip:3128
* configure shared drives
* change db connection host in config from `localhost` to `db`
* add to access list ip 172.18.0.1

Usage
=====

Build
-----
```
docker-compose build
```

Run
---
```
docker-compose up -d
```

Build and run
-------------
```
docker-compose up -d --build
```

Stop
----
```
docker-compose stop
```

Stop and erase containers
-------------------------
```
docker-compose down
```

Stop and erase containers and volumes
-------------------------------------
```
docker-compose down --volumes
```

Run shell
---------
```
docker-compose run php bash
```

List running containers
-----------------------
```
docker-compose ps
```
```
docker ps
```

List all containers
-------------------
```
docker ps -a
```

Show apache configuration
-------------------------
```
docker-compose run php apachectl -S 
```

Show apache modules
-------------------
```
docker-compose run php apachectl -M 
```

Show container logs
-------------------------
```
docker-compose logs -f php 
```
```
docker-compose logs -f db 
```

Backup and restore data volume
------------------
```
docker volume ls
docker run -it -v fxspider_fxspider-db-data:/volume -v /tmp:/backup alpine tar -cjf /backup/fxspider1.tar.bz2 -C /volume ./
docker run -it -v skladvaltek_sklad-valtek-data:/volume -v /tmp:/backup alpine tar -cjf /backup/sklad_valtek.tar.bz2 -C /volume ./
```
```
docker run -it -v some_volume:/volume -v /tmp:/backup alpine sh -c "rm -rf /volume/* /volume/..?* /volume/.[!.]* ; tar -C /volume/ -xjf /backup/some_archive.tar.bz2"
```

TODO
----
* db
  + load test dump
  + prepare container
  + mariadb
  * load full dump
  + remove mysql-data folder
  * my.cnf
+ composer global
  + codeception
* phpmyadmin
  * select db
  * auto login
* php modules
  + ldap
  + stomp
  + tidy
  * xdebug
  * ldap ssl
* ssl certificates
  * apache
  * curl
  * other in php.ini
* remove nginx-related files
* logs
* local docker config
* multi-project ?
* proxy
* proxy in container? https://hub.docker.com/r/jaschac/cntlm/
* docker secrets
* parser
