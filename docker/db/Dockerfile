FROM postgres:10

ENV TZ="Asia/Tokyo"
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && \
    echo $TZ > /etc/timezone
RUN localedef -i ja_JP -c -f UTF-8 -A /usr/share/locale/locale.alias ja_JP.UTF-8

COPY ./db/sql/ /docker-entrypoint-initdb.d/