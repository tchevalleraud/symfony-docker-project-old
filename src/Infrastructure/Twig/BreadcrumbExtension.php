<?php
    namespace App\Infrastructure\Twig;

    use Symfony\Component\HttpFoundation\Request;
    use Twig\Environment;
    use Twig\Extension\AbstractExtension;
    use Twig\TwigFunction;

    class BreadcrumbExtension extends AbstractExtension {

        private $environment;
        private $request;

        public function __construct(Environment $environment){
            $this->environment = $environment;
        }

        public function getFunctions() {
            return [
                new TwigFunction('breadcrumb', [$this, 'getBreadcrumb'], ['is_safe' => ['html']]),
            ];
        }

        public function getBreadcrumb(Request $request, $params = []){
            $this->request = $request;
            $this->analyseParams($params);
            return $this->environment->render("_assets/partials/render.breadcrumb.html.twig", [
                'items' => $this->request->attributes->get('_breadcrumb')
            ]);
        }

        private function analyseParams($params){
            $breadcrumb = $this->request->attributes->get('_breadcrumb');
            foreach ($breadcrumb as $k => $v){
                preg_match("#\#(.*)\##", $v['label'], $d);
                if(!empty($d)){
                    $breadcrumb[$k]['label'] = preg_replace("#\#(.*)\##", $params[$d[1]], $breadcrumb[$k]['label']);
                }
            }
            $this->request->attributes->set('_breadcrumb', $breadcrumb);
        }

    }