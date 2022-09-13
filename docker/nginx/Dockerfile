FROM nginx:1.17.8-alpine

# Copy the public directory
COPY ./phpservice/ /var/www/html/phpservice

# Copy the nginx config file
COPY nginx.conf /etc/nginx/conf.d/default.conf
