<?php

namespace ProcessWire;

// Load class
wire('classLoader')->addNamespace('App\Controller', $config->paths->templates . 'Controller');
wire('classLoader')->addNamespace('App\Services', $config->paths->templates . 'Services');
wire('classLoader')->addNamespace('App\Core', $config->paths->templates . 'Core');