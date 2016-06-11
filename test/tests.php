<?php
use \AppZz\Helpers\Arr as Arr;
require_once (dirname(__DIR__) . '/src/Arr.php');

$arr = [
	'foo' => '123',
	'bar' => '456',
	'qwe' => [
		'a'=>'ert',
		'b'=>'asd',
		'c'=>[
			'aa'=>2,
			'bb'=>3
		]
	]
];

var_dump(Arr::get($arr, 'foo'));
var_dump(Arr::get($arr, 'foo1'));
var_dump(Arr::get($arr, 'foo1', 3));
var_dump(Arr::get($arr, 'bar'));
var_dump(Arr::path($arr, 'qwe.a'));
var_dump(Arr::path($arr, 'qwe.b'));
var_dump(Arr::path($arr, 'qwe.aa'));
var_dump(Arr::path($arr, 'qwe.c.aa'));
var_dump(Arr::path($arr, 'qwe#c#aa', '#'));
var_dump(Arr::path($arr, 'qwe#c#aaa', '#', 22));