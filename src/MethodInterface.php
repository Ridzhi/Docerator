<?php

namespace DocBuilder;


interface MethodInterface
{

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * @param string|array $return
     * @return $this
     */
    public function setReturn($return);

    /**
     * @param string $name
     * @param string|null $type
     * @return $this
     */
    public function setArgument($name, $type = null);

}