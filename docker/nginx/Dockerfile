ARG NGINX_VERSION=1.19.9

FROM nginx:${NGINX_VERSION}-alpine

COPY ./docker/nginx/app.conf /etc/nginx/conf.d/default.conf

CMD [ "nginx", "-g", "daemon off;" ]
