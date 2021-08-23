<?php
    namespace App\Application\Annotation\Breadcrumb;

    use Doctrine\Common\Annotations\Reader;
    use Symfony\Component\HttpKernel\Event\ControllerEvent;

    class BreadcrumbReader {

        private $breadcrumbDatabase = [];
        private $breadcrumb = [];
        private $reader;
        private $request;

        public function __construct(Reader $reader){
            $this->reader = $reader;
        }

        public function onKernelController(ControllerEvent $event){
            if(!is_array($controllers = $event->getController())) return;

            $this->request = $event->getRequest();
            $this->handleAnnotation($controllers);

            $event->getRequest()->attributes->set('_breadcrumb', $this->breadcrumb);
        }

        private function handleAnnotation(iterable $controllers){
            list($controller, $method) = $controllers;

            try {
                $controller = new \ReflectionClass($controller);
            } catch (\ReflectionException $e){
                throw new \RuntimeException('Failed to read annotation !');
            }

            $this->handleClassAnnotation($controller);
            $this->handleMethodAnnotation($controller, $method);
        }

        private function handleClassAnnotation(\ReflectionClass $controller){
            $annotations = $this->reader->getClassAnnotations($controller);

            foreach ($annotations as $annotation){
                if($annotation instanceof Breadcrumb) $this->breadcrumbDatabase[$annotation->key] = $annotation;
            }
        }

        private function handleMethodAnnotation(\ReflectionClass $controller, string $method){
            $method = $controller->getMethod($method);
            $annotation = $this->reader->getMethodAnnotation($method, Breadcrumb::class);

            if($annotation instanceof Breadcrumb) {
                $key = $annotation->key;
                $this->analyseBreadcrumb($key);
                $this->analyseParamsBreadcrumb();
            }
        }

        private function analyseBreadcrumb($index){
            if(array_key_exists($index, $this->breadcrumbDatabase)){
                if(array_key_exists("parent", $this->breadcrumbDatabase[$index])){
                    $this->analyseBreadcrumb($this->breadcrumbDatabase[$index]->parent);
                }
                $this->breadcrumb[] = [
                    'label'     => $this->breadcrumbDatabase[$index]->label,
                    'route'     => $this->breadcrumbDatabase[$index]->route,
                    'params'    => (array_key_exists("params", $this->breadcrumbDatabase[$index]) ? $this->breadcrumbDatabase[$index]->params : [])
                ];
            }
        }

        private function analyseParamsBreadcrumb(){
            foreach ($this->breadcrumb as $k => $breadcrumb){
                if(!empty($breadcrumb["params"])){
                    $params = [];
                    foreach ($breadcrumb["params"] as $param){
                        $params[$param] = $this->request->attributes->get($param);
                    }
                    $this->breadcrumb[$k]["params"] = $params;
                }
            }
        }

    }