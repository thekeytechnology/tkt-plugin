<?php

function tkAddUtilFunctions(TkTemplate $tkTwig)
{
    $tkTwig->addFunction("tkwritelog", function ($item) {
        tkWriteLog($item);
    });
}