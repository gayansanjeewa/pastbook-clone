FROM ubuntu:18.04

LABEL maintainer="GeekSet.io <geekset.tv@gmail.com>"

ENV DEBIAN_FRONTEND=noninteractive

ENV PATH /usr/local/rvm/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin

RUN apt update \
    && apt install -y beanstalkd

RUN apt clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/

VOLUME ["/data"]
EXPOSE 11300
CMD ["/usr/bin/beanstalkd", "-f", "60000", "-b", "/data"]