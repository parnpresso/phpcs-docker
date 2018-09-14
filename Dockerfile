FROM ubuntu:18.04

RUN DEBIAN_FRONTEND=noninteractive

RUN apt update
RUN apt install tzdata -yqq
RUN apt install php7.2-cli php7.2-fpm php7.2-curl php7.2-gd php7.2-mysql php7.2-mbstring php7.2-xml zip unzip -yqq
RUN apt install composer -yqq

RUN mkdir /app
ADD ./app /app

RUN composer create-project wp-coding-standards/wpcs --no-dev
RUN rm -rf /app/wpcs
RUN mv /wpcs /app/

ENV PATH=$PATH:/app/wpcs/vendor/bin

CMD tail -f /dev/null
