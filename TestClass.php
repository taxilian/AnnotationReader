<?php

require_once("AnnotatedClass.php");

use taxilian\AnnotationReader\AnnotatedClass;

/**
 * @annotation1
 * @annotation2(1,2,3,4)
 * @annotation3("asdf", "bfdsa", true, false, TRUE, "123")
 * @annotation4()
 * @allowOptions(GET, POST)
 * @disallowOptions(PUT, DELETE)
 **/
class TestClass {
    /**
     * @var("GET", "a", "b", "c")
     * @temporary
     **/
    public function init() {
        
    }
};

$ac = new AnnotatedClass("TestClass");

$res = $ac->getClassAnnotations();
$res2 = $ac->getMethodAnnotations("init");

print_r($res);
print_r($res2);
