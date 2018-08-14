<?php
/**
 * Created by PhpStorm.
 * User: devmaker
 * Date: 14/08/18
 * Time: 14:06
 */

namespace App\Models;


use Carbon\Carbon;

class Hora
{
    private $hora;
    private $minuto;
    private $horaCompleta;

    /**
     * Hora constructor.
     * @param $horaCompleta
     */
    public function __construct($horaCompleta)
    {
        $this->horaCompleta = $horaCompleta;

        list($this->hora, $this->minuto) = explode(':', $horaCompleta);
    }

    /**
     * @return mixed
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * @return mixed
     */
    public function getMinuto()
    {
        return $this->minuto;
    }

    /**
     * @return Carbon
     */
    public function toCarbon()
    {
        return Carbon::createFromFormat('H:i', $this->horaCompleta);
    }

    /**
     * @return mixed
     */
    public function getHoraCompleta()
    {
        return $this->horaCompleta;
    }
}
