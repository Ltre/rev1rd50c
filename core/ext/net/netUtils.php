<?php

function isPost(){
    return strtolower($_SERVER['REQUEST_METHOD']) == 'post';
}