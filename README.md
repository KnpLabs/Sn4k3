Sn4k3
=====

![Frendly snek](snake.jpg)

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

## Destroying items

A "destroy" workflow has been implemented to avoid memory leaks because of referenced objects.

The "first" thing that can be destroyed is a `Player` object, once the snake dies.

If a player looses, there are recursive calls to every `DestroyableInterface` object that could be one of the 
 properties of the so-called destroyable object.

In the case of the `Player` object, this is how objects are deleted:

1. Call `Player->destroy()`
2. Will destroy the `Snake`
3. Will destroy the `Snake::$bodyParts` which is a `CirclesList` instance
4. Will destroy every circle which are `Circle` instances
5. Will destroy the `Circle::$centerPoint` property
 
Everything is `unset()` and manually set to `null` so we make sure memory is completely fred of any possible object
 reference that would introduce memory leaks.
