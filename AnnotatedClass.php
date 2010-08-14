<?php

namespace taxilian\AnnotationReader;

// Support a couple of basic types of annotations.  One per line.

/**
 * @decorator
 * @decorator(option1, option2)
 * @accept("post", 1234, "asdf")
 **/
class AnnotatedClass extends \ReflectionClass {
    protected $_reflectionClass;

    public function __construct($arg) {
        parent::__construct($arg);
    }

    protected function parseLine($line) {
        if (substr($line, 0, 1) != "@") {
            return array();
        } else if (($stPos = strpos($line, "(")) !== false) {
            $name = substr($line, 1, $stPos-1);
            $endPos = strrpos($line, ")") ?: strlen($line);
            $opts = substr($line, $stPos+1, $endPos-$stPos-1);
            $opts = explode(",", $opts);
            foreach ($opts as $i=>$v) {
                $v = trim($v);
                $char = substr($v, 0, 1);
                if ($char == '"' || $char == "'") {
                    $opts[$i] = trim($v, " \"'");
                } else if (is_numeric($v)) {
                    $opts[$i] = doubleval($v);
                } else {
                    $opts[$i] = $v;
                }
            }
            return array($name => $opts);
        } else {
            $name = substr($line, 1);
            return array($name => "");
        }
    }

    protected function parseDocComment($str) {
        $annotations = array();
        $lines = explode("\n", $str);
        foreach ($lines as $line) {
            $line = trim($line, "\r\n*/ ,");
            $annotations = array_merge($annotations, $this->parseLine($line));
        }
        return $annotations;
    }

    public function getClassAnnotations() {
        $str = $this->getDocComment();
        return $this->parseDocComment($str);
    }

    public function getMethodAnnotations($methodName) {
        $method = $this->getMethod($methodName);
        $str = $method->getDocComment();
        return $this->parseDocComment($str);
    }
}
