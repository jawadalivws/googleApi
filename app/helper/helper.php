<?php

function getTimeAgo($carbonObject) {

    if($carbonObject != null){
        return str_ireplace(
            [' seconds', ' second', ' minutes', ' minute', ' hours', ' hour', ' days', ' day', ' weeks', ' week'], 
            ['s', 's', 'm', 'm', 'h', 'h', 'd', 'd', 'w', 'w'], 
            $carbonObject->diffForHumans()
        );
    }

}

function getCompain()
{
    return  [
        '001' => 'Default Compain',
        '002' => 'Compain 2',
        '003' => 'Compain 3',
        '004' => 'Compain 4',
        '005' => 'Compain 5',
    ];
}