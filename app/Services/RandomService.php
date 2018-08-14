<?php
/**
 * Created by PhpStorm.
 * User: devmaker
 * Date: 14/08/18
 * Time: 13:59
 */

namespace App\Services;


use App\Models\Hora;

class RandomService
{
    private $fileName = 'horas.json';
    private $horas;

    /**
     * @return \Illuminate\Support\Collection
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getHoras()
    {
        if($this->horas){
            return $this->horas;
        }

        $this->horas = collect();
        foreach(json_decode(\Storage::get($this->fileName), true)['horas'] as $hora){
            $this->horas[] = new Hora($hora);
        }

        return $this->horas;
    }
}
