Block Relocation Problem Solver
===============================

Getting Started
---------------

Add ``xabbuh/block-relocation-problem`` to your ``composer.json`` file:

```json
{
    "require": {
        "xabbuh/block-relocation-problem": "~1.0@dev"
    }
}
```

and run ``php composer.phar update``.

Container Configuration
-----------------------

Create a file containing your BRP configuration (e.g. ``configuration.json``):

```json
{
    "stacks": [
        [6, 7, 9],
        [1, 3, 4],
        [2, 8, 5]
    ]
}
```

Solve the Problem
-----------------

Solve the problem running the ``brp:configuration:solve`` command:

```bash
$ php vendor/bin/bpr.php brp:configuration:solve --algorithm=la configuration.json
```
