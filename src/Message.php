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
    public function headers();

    /**
     * @return array
     */
    public function body();
}
