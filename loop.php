<?php

$colors = [
    'white',
    'grey',
    'orange',
    'dark',
    'blue',
    'neon',
    'yellow',
    'red',
    'green',
];
for ($i=0; $i<sizeof($colors);$i++){
    echo ".bg-$colors[$i]{background: var(--pm-$colors[$i])}".PHP_EOL;
    echo ".text-$colors[$i]{color: var(--pm-$colors[$i])}".PHP_EOL;
    echo ".bg-outline-$colors[$i]{border:1px solid var(--pm-$colors[$i]); color:var(--pm-$colors[$i])}".PHP_EOL;
    echo ".bg-outline-$colors[$i]:hover{background-color: var(--pm-$colors[$i]);}".PHP_EOL;
    echo PHP_EOL;
}


