<?php

class imagenTextoCentrado {

    private $imagen;
    private $width; 
    private $widthLimite; 
    private $interlineado;
    private $fuente;
    private $bgColor;
    private $texto;
    private $textoSize;
    private $textoColor;
    private $textoPie;
    private $textoPieSize;
    private $textoLineas;

    public function __construct( int $width, float $maxWidth)
    {
        $this->width = $width;
        $this->widthLimite = $width * $maxWidth;
        $this->interlineado = 1.5;
        $this->textoSize = 18;
        $this->textoPieSize = 12;
        $this->bgColor = [0, 0, 0];
        $this->textoColor = [255, 255, 255];
    }    

    public function setFuente(string $ruta)
    {
        $this->fuente = $ruta;
    }

    public function setInterlineado(float $interlineado)
    {
        $this->interlineado = $interlineado;
    }

    public function setBgColor(array $rgb) {
        $this->bgColor = $rgb;
    }

    public function setTexto(string $texto)
    {
        $this->texto = $texto;
    }

    public function setTextoSize(int $size)
    {
        $this->textoSize = $size;
    }

    public function setTextoColor(array $rgb)
    {
        $this->textoColor = $rgb;
    }

    public function setTextoPie(string $texto)
    {
        $this->textoPie = $texto;
    }

    public function setTextoPieSize(int $size)
    {
        $this->textoPieSize = $size;
    }
 

    private function generarLineas()
    {
        if(empty($this->texto)) {
            throw new Exception("La propiedad \"texto\" esta vacia.");
        } else if (empty($this->texto)) {
            throw new Exception("No ha especificado la \"ruta\" de la fuente.");
        }

        $palabras = explode(' ', $this->texto);
    
        $palabrasLimpiadas = [];
    
        foreach($palabras as $elemento) {
            $elemento = trim($elemento);
            if (!empty($elemento)) {
                $palabrasLimpiadas[] = $elemento;
            }
        }

        $numeroPalabras = count($palabrasLimpiadas);
        $lineas = [];
        $lineaTemporar = '';
        for ($i=0; $i < $numeroPalabras; $i++) { 

            $lineaTemporar .= $palabrasLimpiadas[$i] . ' ';
            $coords = imagettfbbox($this->textoSize, 0, $this->fuente, trim($lineaTemporar));
            if ( $coords[2] > $this->widthLimite || $i === ($numeroPalabras - 1) ) {
                $lineas[] = trim($lineaTemporar);
                $lineaTemporar = '';
            }
        }

        $this->textoLineas = $lineas;
    }

    private function calcularHeight(): int
    { 
        $textoLineas = count($this->textoLineas);
        $height = ( ( $textoLineas + 9 ) * $this->textoSize ) + ( ( $textoLineas - 1 ) * ($this->interlineado * $this->textoSize) );

        return $height;
    }

    public function prepararImagen()
    {
        $this->generarLineas();
        $height = $this->calcularHeight();

        $this->imagen = imagecreate($this->width, $height);

        list($r, $g, $b) = $this->bgColor;
        $colorFondo  = imagecolorallocate($this->imagen, $r, $g, $b);

        list($r, $g, $b) = $this->textoColor;
        $colorTexto = imagecolorallocate($this->imagen, $r, $g, $b);

        $posicionLinea =  $this->textoSize * 4;
        $espacioLineaRelativo =  $this->textoSize + ($this->interlineado * $this->textoSize);
        $textoLineas = count($this->textoLineas);
        for ($i=0; $i < $textoLineas; $i++) {  

            if($i) {
                $posicionLinea +=  $espacioLineaRelativo;
            }
    
            // Centrar Línea
            $coords = imagettfbbox($this->textoSize, 0, $this->fuente, $this->textoLineas[$i]);
            $centrarX = ($this->width - $coords[2]) / 2;
    
    
            imagettftext($this->imagen, $this->textoSize, 0, $centrarX, $posicionLinea, $colorTexto, $this->fuente, $this->textoLineas[$i]);
        }

        if (!empty($this->textoPie)) {
            $coords = imagettfbbox($this->textoPieSize, 0, $this->fuente, $this->textoPie);
            $anchoTexto = $coords[2];

            $posicionX = $this->width - ($anchoTexto + $this->textoSize * 2);
            $posicionY = $height - ( $this->textoSize * 2);

            imagettftext($this->imagen, $this->textoPieSize, 0, $posicionX, $posicionY, $colorTexto, $this->fuente, $this->textoPie);
        }
    }

    public function guardar($ruta) {
        if ( empty($ruta) ) {
            throw new Exception("La \"ruta\" para guardar la imagen esta vacia");
        } else if ( empty($this->imagen) ) {
            throw new Exception("La \"imagen\" aun no ha sido preparada. por esta razón no puede ser guardad");
        }

        header('Content-Type: image/png');
        imagepng($this->imagen);
        ## imagepng($this->imagen, $ruta);
        imagedestroy($this->imagen);
    } 
}
