FROM mysql:8.4
COPY gamefest.sql /docker-entrypoint-initdb.d/

# docker run -it --rm --name tests-sql -p 33060:3306 -v datadir:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=pass --network reto_2_my-network test-image
# docker build -t test-image .
# docker image rm test-image