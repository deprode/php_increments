FROM postgres:10

ENV TZ="Asia/Tokyo"
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && \
    echo $TZ > /etc/timezone

COPY ./db/sql/ /docker-entrypoint-initdb.d/