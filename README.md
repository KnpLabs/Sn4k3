Sn4k3
=====

![Frendly snek](snake.jpg)

# Installation

Install dependencies

```bash
cd backend/
composer install
pip install crossbar
npm install -g browserify
browserify frontend/src/app/app.js > frontend/public/bundle.js
```

Configure your web-server at `frontend/public`

:information_source: If you want to install it with docker, please follow this link:<br>
http://crossbar.io/docs/Installation-on-Docker/


# Execute

Run backend crossbar instance

```bash
crossbar start
```
