<?php

namespace MiniBus;

interface Message
{
    /**
     * @return string
     */
    public function subject();

    /**
     * @return array
     */
    public function normalize();
}
