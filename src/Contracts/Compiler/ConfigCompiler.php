<?php

namespace LTFramework\Contracts\Compiler;


interface ConfigCompiler 
{
    public function write($dataRaw = '', $vendor, $name, $i = null);

    public function getArrayDataPlugins();

    public function getIndexFromPlugins($arrayElem, $vendor, $name);

    public function updatePermission($vendor, $name);

    public function migration($vendor, $namePlugin, $kind);
}