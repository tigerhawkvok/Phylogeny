<?php

// Definition framework

function defineword($word,$def)
{
$popcode="<div class='define'><p class='genpara'>$word</p><ul class='popdef'><li class='popdef'>$def</li></ul></div>";

return $popcode;
}

?>