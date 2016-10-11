Sn4k3
=====

![Frendly snek](snake.jpg)

![Frendly snek demo](sn4k3.gif)

# Installation

Install dependencies

```bash
# Root install of crossbar
pip install crossbar

# Backend dependencies
cd backend/
composer install

# Frontend dependencies
cd frontend/
npm install
npm run build
# In dev, you can run this:
npm run watch
```

Configure your web-server at `frontend/public`

:information_source: If you want to install it with docker, please follow this link:<br>
http://crossbar.io/docs/Installation-on-Docker/

# Execute

Run Crossbar middleware instance

```bash
crossbar start
```

And run the php broadcast application

```bash
php backend/broadcast.php
```

# Bottlenecks

## Collisions

On EVERY tick, the app checks collisions for every "snake head vs any other object".

In this case, we make other naive/less precise assumptions.

1. First, we check the distance between each object's center (because we're making comparisons on circles, most of the
 time). It's very fast because it just compares two values.
2. Next, we calculate a rectangle hitbox collision, which is slower but still faster than a precise text.
3. And finally we make a geometrical comparison of circles, which is very slow.

Here are some benchmarks about this:

| Assumption type    | Naive  | Rectangle | Circle |
| ------------------ | ------ | --------- | ------ |
| Total calculations | 200000 | 70000     | 20     |
| Average time       | 1500   | 8300      | 18000  |
