<?php
    namespace App\Infrastructure\Profiler\PHPUnit;

    use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    class RequestProfiler extends AbstractDataCollector {

        protected $filePath = __DIR__."/../../../../testdox.xml";

        protected $xml;

        public function collect(Request $request, Response $response, \Throwable $exception = null) {
            $data = [
                'error'     => 0,
                'ok'        => 0,
                'tests'     => [],
                'time'      => 0,
            ];

            if(file_exists($this->filePath)){
                $this->xml = simplexml_load_file($this->filePath, "SimpleXMLIterator");

                foreach ($this->xml->test as $test){
                    $d = (array) $test;
                    $className  = $d['@attributes']['className'];
                    $methodName = $d['@attributes']['methodName'];
                    $status     = $d['@attributes']['status'];
                    $time       = $d['@attributes']['time'];

                    if($status == 0) $data['ok'] = $data['ok'] + 1;
                    else $data['error'] = $data['error'] + 1;

                    $data['tests'][$className.":".$methodName] = [
                        'className'     => $className,
                        'methodName'    => $methodName,
                        'status'        => $status,
                        'time'          => $time
                    ];

                    $data['time'] = $data['time'] + $time;
                }


                ksort($data['tests']);
                $data['time'] = round($data['time'], 2);
            }

            $this->data = $data;
        }

        public static function getTemplate(): ?string {
            return '_profiler/PHPUnit/template.html.twig';
        }

        public function getError(){
            return $this->data['error'];
        }

        public function getOk(){
            return $this->data['ok'];
        }

        public function getTests(){
            return $this->data['tests'];
        }

        public function getTime(){
            return $this->data['time'];
        }

    }