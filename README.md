Block Relocation Problem Solver
===============================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xabbuh/brp-php/badges/quality-score.png?s=959ebb6486f936bb55c48063342eb24890169357)](https://scrutinizer-ci.com/g/xabbuh/brp-php/)
[![Build Status](https://travis-ci.org/xabbuh/brp-php.svg)](https://travis-ci.org/xabbuh/brp-php)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/8e20773b-3736-4a67-b8df-962238c98979/mini.png)](https://insight.sensiolabs.com/projects/8e20773b-3736-4a67-b8df-962238c98979)

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
$ php vendor/bin/brp.php brp:configuration:solve --algorithm=la configuration.json
```
