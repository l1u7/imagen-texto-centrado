<?php

include 'imagenTextoCentrado.php';


$imagen = new imagenTextoCentrado(800, .6);

$imagen->setFuente('./NotoSansJP-Light.otf');
$imagen->setTexto("¡Feliz Día Amigo! Que la vida te sonría hoy y siempre y que encuentres todo eso, que le trae felicidad a tu corazón."); 
$imagen->setTextoColor([55, 230, 99]);
$imagen->setBgColor([16, 1, 18]);
$imagen->setTextoPie("- Luis Felipe");
$imagen->prepararImagen();
$imagen->guardar('./amigo.png');
