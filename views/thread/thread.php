<?php

use React\EventLoop\Factory;

$loop = Factory::create();
$loop->addTimer(0, function () {
    echo "hi   ";
});
echo "hello   ";
$loop->run();


$this->registerJs(<<<JS
 // console.log('Hi')
 // setTimeout(()=>{
 //     console.log('log this after 20ms');},20);
 // setTimeout(()=>{
 //     console.log('log this after 0ms');},0);
 // console.log('ok')

JS

)
?>

