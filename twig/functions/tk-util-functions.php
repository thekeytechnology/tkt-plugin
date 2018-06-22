<?php

function tkAddUtilFunctions(TkTemplate $tkTwig)
{
    $tkTwig->addFunction("tkwritelog", function ($item) {
        tkWriteLog($item);
    });

    $tkTwig->addFunction("calluserfunc", function ($name, $parameter = null, $_ = null) {

        return call_user_func($name, $parameter, $_);
    });
}