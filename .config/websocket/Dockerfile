FROM node:12

RUN mkdir /app

WORKDIR /app

COPY laravel-echo-server.json laravel-echo-server.json

RUN npm install -g laravel-echo-server

EXPOSE 6001
CMD ["laravel-echo-server", "start"]
