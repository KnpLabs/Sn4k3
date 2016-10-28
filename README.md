Sn4k3 - next generation MMO
===========================

![Frendly snek](snake.jpg)

![Frendly snek demo](sn4k3.gif)

#### Documentation index

* [Installation](#install)
* [Run](#run)
* [Bottlenecks](#nottlenecks)
* [Backend readme](backend/README.md)
* [Frontend readme](frontend/README.md)

# Requirements <small>(if you don't have Docker)</small>

* PHP >=7.0
* Python >=2.7 (with `pip`)
* NodeJS >=4.4 (with `npm`)

# Install

## With Docker

Just run `docker-compose build`.

## Without Docker

Execute all these commands directly from the root directory of the repository.

```bash
pip install crossbar
composer --working-dir=backend install
npm install ./frontend/
npm run build
```

# Run

## With Docker

Just execute `docker-compose up -d`

And go to `127.0.0.1:8080`.

## Without docker

You will need at least 3 terminals for this, because all apps need to be run independently.

Run Crossbar middleware instance

```bash
crossbar start --config=crossbar/config.json
```

Run the php broadcast application

```bash
php backend/broadcast.php
```

And finally execute nodejs static server

```bash
node frontend/server.js
```

And go to `127.0.0.1:8080`.

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
