<?php
namespace Simon\controller;

class KiwangoSecurity
{
    public function guard($data) {
        if (! isset($data)) {
            return false;
        }

        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }
}