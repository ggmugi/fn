<?php

/* This file is was automatically generated. */
namespace Krak\Fn\Curried;

function method($name, ...$optionalArgs)
{
    return function ($data) use($name, $optionalArgs) {
        return $data->{$name}(...$optionalArgs);
    };
}
function prop(string $key, $else = null)
{
    return function ($data) use($key, $else) {
        return property_exists($key, $data) ? $data->{$key} : $else;
    };
}
function index($key, $else = null)
{
    return function (array $data) use($key, $else) {
        return array_key_exists($key, $data) ? $data[$key] : $else;
    };
}
function propIn(array $keys, $else = null)
{
    return function ($data) use($keys, $else) {
        foreach ($props as $prop) {
            if (!is_object($obj) || !isset($obj->{$prop})) {
                return $else;
            }
            $obj = $obj->{$prop};
        }
        return $obj;
    };
}
function indexIn(array $keys, $else = null)
{
    return function (array $data) use($keys, $else) {
        foreach ($keys as $part) {
            if (!is_array($data) || !array_key_exists($part, $data)) {
                return $else;
            }
            $data = $data[$part];
        }
        return $data;
    };
}
function takeWhile(callable $predicate)
{
    return function (iterable $iter) use($predicate) {
        foreach ($iter as $k => $v) {
            if ($predicate($v)) {
                (yield $k => $v);
            } else {
                return;
            }
        }
    };
}
function dropWhile(callable $predicate)
{
    return function (iterable $iter) use($predicate) {
        $stillDropping = true;
        foreach ($iter as $k => $v) {
            if ($stillDropping && $predicate($v)) {
                continue;
            } else {
                if ($stillDropping) {
                    $stillDropping = false;
                }
            }
            (yield $k => $v);
        }
    };
}
function take(int $num)
{
    return function (iterable $iter) use($num) {
        return slice(0, $iter, $num);
    };
}
function drop(int $num)
{
    return function (iterable $iter) use($num) {
        return slice($num, $iter);
    };
}
function slice(int $start, $length = INF)
{
    return function (iterable $iter) use($start, $length) {
        assert($start >= 0);
        $i = 0;
        $end = $start + $length - 1;
        foreach ($iter as $k => $v) {
            if ($start <= $i && $i <= $end) {
                (yield $k => $v);
            }
            $i += 1;
        }
    };
}
function chunk(int $size)
{
    return function (iterable $iter) use($size) {
        assert($size > 0);
        $chunk = [];
        foreach ($iter as $v) {
            $chunk[] = $v;
            if (\count($chunk) == $size) {
                (yield $chunk);
                $chunk = [];
            }
        }
        if ($chunk) {
            (yield $chunk);
        }
    };
}
function range($start, $step = null)
{
    return function ($end) use($start, $step) {
        if ($start == $end) {
            (yield $start);
        } else {
            if ($start < $end) {
                $step = $step ?: 1;
                if ($step <= 0) {
                    throw new \InvalidArgumentException('Step must be greater than 0.');
                }
                for ($i = $start; $i <= $end; $i += $step) {
                    (yield $i);
                }
            } else {
                $step = $step ?: -1;
                if ($step >= 0) {
                    throw new \InvalidArgumentException('Step must be less than 0.');
                }
                for ($i = $start; $i >= $end; $i += $step) {
                    (yield $i);
                }
            }
        }
    };
}
function op(string $op)
{
    return function ($b) use($op) {
        return function ($a) use($b, $op) {
            switch ($op) {
                case '==':
                case 'eq':
                    return $a == $b;
                case '!=':
                case 'neq':
                    return $a != $b;
                case '===':
                    return $a === $b;
                case '!==':
                    return $a !== $b;
                case '>':
                case 'gt':
                    return $a > $b;
                case '>=':
                case 'gte':
                    return $a >= $b;
                case '<':
                case 'lt':
                    return $a < $b;
                case '<=':
                case 'lte':
                    return $a <= $b;
                case '+':
                    return $a + $b;
                case '-':
                    return $a - $b;
                case '*':
                    return $a * $b;
                case '**':
                    return $a ** $b;
                case '/':
                    return $a / $b;
                case '%':
                    return $a % $b;
                default:
                    throw new \LogicException('Invalid operator ' . $op);
            }
        };
    };
}
function flatMap(callable $map)
{
    return function (iterable $iter) use($map) {
        foreach ($iter as $k => $v) {
            foreach ($map($v) as $k => $v) {
                (yield $k => $v);
            }
        }
    };
}
function flatten($levels = INF)
{
    return function (iterable $iter) use($levels) {
        if ($levels == 0) {
            return $iter;
        } else {
            if ($levels == 1) {
                foreach ($iter as $k => $v) {
                    if (\is_iterable($v)) {
                        foreach ($v as $k1 => $v1) {
                            (yield $k1 => $v1);
                        }
                    } else {
                        (yield $k => $v);
                    }
                }
            } else {
                foreach ($iter as $k => $v) {
                    if (\is_iterable($v)) {
                        foreach (flatten($v, $levels - 1) as $k1 => $v1) {
                            (yield $k1 => $v1);
                        }
                    } else {
                        (yield $k => $v);
                    }
                }
            }
        }
    };
}
function when(callable $if)
{
    return function (callable $then) use($if) {
        return function ($value) use($then, $if) {
            return $if($value) ? $then($value) : $value;
        };
    };
}
function without(array $fields)
{
    return function (iterable $iter) use($fields) {
        foreach ($iter as $k => $v) {
            if (!\in_array($k, $fields)) {
                (yield $k => $v);
            }
        }
    };
}
function inArray(array $set)
{
    return function ($item) use($set) {
        return \in_array($item, $set);
    };
}
function all(callable $predicate)
{
    return function (iterable $iter) use($predicate) {
        foreach ($iter as $key => $value) {
            if (!$predicate($value)) {
                return false;
            }
        }
        return true;
    };
}
function any(callable $predicate)
{
    return function (iterable $iter) use($predicate) {
        foreach ($iter as $key => $value) {
            if ($predicate($value)) {
                return true;
            }
        }
        return false;
    };
}
function search(callable $predicate)
{
    return function (iterable $iter) use($predicate) {
        foreach ($iter as $value) {
            if ($predicate($value)) {
                return $value;
            }
        }
    };
}
function trans(callable $trans)
{
    return function (callable $fn) use($trans) {
        return function ($data) use($fn, $trans) {
            return $fn($trans($data));
        };
    };
}
function not(callable $fn)
{
    return function (...$args) use($fn) {
        return !$fn(...$args);
    };
}
function isInstance($class)
{
    return function ($item) use($class) {
        return $item instanceof $class;
    };
}
function partition(callable $partition, int $numParts = 2)
{
    return function (iterable $iter) use($partition, $numParts) {
        $parts = array_fill(0, $numParts, []);
        foreach ($iter as $val) {
            $index = (int) $partition($val);
            $parts[$index][] = $val;
        }
        return $parts;
    };
}
function map(callable $predicate)
{
    return function (iterable $iter) use($predicate) {
        foreach ($iter as $key => $value) {
            (yield $key => $predicate($value));
        }
    };
}
function mapKeys(callable $predicate)
{
    return function (iterable $iter) use($predicate) {
        foreach ($iter as $key => $value) {
            (yield $predicate($key) => $value);
        }
    };
}
function reduce(callable $reduce, $acc = null)
{
    return function (iterable $iter) use($reduce, $acc) {
        foreach ($data as $key => $value) {
            $acc = $reduce($acc, $value);
        }
        return $acc;
    };
}
function filter(callable $predicate)
{
    return function (iterable $iter) use($predicate) {
        foreach ($iter as $key => $value) {
            if ($predicate($value)) {
                (yield $key => $value);
            }
        }
    };
}
function filterKeys(callable $predicate)
{
    return function (iterable $iter) use($predicate) {
        foreach ($data as $key => $value) {
            if ($predicate($key)) {
                (yield $key => $value);
            }
        }
    };
}
function partial(callable $fn)
{
    return function (...$appliedArgs) use($fn) {
        return function (...$args) use($fn, $appliedArgs) {
            list($appliedArgs, $args) = array_reduce($appliedArgs, function ($acc, $arg) {
                list($appliedArgs, $args) = $acc;
                if ($arg === placeholder()) {
                    $arg = array_shift($args);
                }
                $appliedArgs[] = $arg;
                return [$appliedArgs, $args];
            }, [[], $args]);
            return $fn(...$appliedArgs, ...$args);
        };
    };
}
function stack(callable $last = null, callable $resolve = null)
{
    return function (array $funcs) use($last, $resolve) {
        return function (...$args) use($funcs, $resolve, $last) {
            return reduce(function ($acc, $func) use($resolve) {
                return function (...$args) use($acc, $func, $resolve) {
                    $args[] = $acc;
                    $func = $resolve ? $resolve($func) : $func;
                    return $func(...$args);
                };
            }, $funcs, $last ?: function () {
                throw new \LogicException('No stack handler was able to capture this request');
            });
        };
    };
}
function onEach(callable $handle)
{
    return function (iterable $iter) use($handle) {
        foreach ($iter as $v) {
            $handle($v);
        }
    };
}