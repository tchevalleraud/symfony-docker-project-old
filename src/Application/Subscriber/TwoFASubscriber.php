<?php
    namespace App\Application\Subscriber;

    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpKernel\Event\ControllerEvent;
    use Symfony\Component\HttpKernel\KernelEvents;
    use Symfony\Component\Routing\RouterInterface;

    class TwoFASubscriber implements EventSubscriberInterface {

        private $router;

        public function __construct(RouterInterface $router){
            $this->router = $router;
        }

        public static function getSubscribedEvents() {
            return [
                KernelEvents::CONTROLLER => 'onKernelController',
            ];
        }

        public function onKernelController(ControllerEvent $event){
            if($this->shouldRedirectToVerificationPage($event->getRequest())){
                $event->setController(function () use ($event){
                    return new RedirectResponse($this->router->generate('app.security.verify'));
                });
            }
        }

        private function shouldRedirectToVerificationPage(Request $request){
            $requestRoute = $request->attributes->get('_route');
            return $this->requiresVerification($requestRoute) && !$this->verificationPassed($request);
        }

        private function requiresVerification($route) {
            $permittedRoutes = [
                'app.security.',
                'api.v2',
                '_profiler',
                '_wdt'
            ];
            $return = true;
            foreach ($permittedRoutes as $r){
                if(str_contains($route, $r)) $return = false;
            }
            return $return;
        }

        private function verificationPassed(Request $request) {
            return $request->getSession()->get('2fa-verified') === true;
        }

    }