FROM node:18-slim

WORKDIR /usr/src/app

COPY noyau_backend/realtime/package*.json ./

RUN npm install || echo "No package.json found, skipping npm install"

EXPOSE 3000

CMD [ "node", "server.js" ]
