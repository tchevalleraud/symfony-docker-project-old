<?php
    namespace App\UI\_default;

    use App\Application\Services\OTPService;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Session\Session;
    use Symfony\Component\Messenger\MessageBusInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

    class SecurityController extends AbstractController {

        /**
         * @Route({
         *     "en": "/en/login.html",
         *     "fr": "/fr/connexion.html"
         * }, name="app.security.login")
         */
        public function login(AuthenticationUtils $authenticationUtils): Response {
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('_security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }

        /**
         * @Route({
         *     "en": "/en/logout.html",
         *     "fr": "/fr/deconnexion.html"
         * }, name="app.security.logout")
         */
        public function logout(){
            throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        }

        /**
         * @Route({
         *     "en": "/en/otp.html",
         *     "fr": "/fr/otp.html"
         * }, name="app.security.verify")
         */
        public function verifyOTP(Request $request, OTPService $OTPService, Session $session, MessageBusInterface $messageBus){
            $error = null;
            $otp_method = $request->get('_otp_method') ? $request->get('_otp_method') : $this->getUser()->getOtp()[0];

            if($session->get('2fa-verified') == true) return $this->redirectToRoute('frontoffice.dashboard.index');

            if($request->getMethod() == "POST"){
                if($this->isCsrfTokenValid('authenticate-otp', $request->get('_csrf_token'))){
                    $token = $request->request->get('_auth_code');

                    try {
                        $OTPService->verifyOTP($otp_method, $this->getUser(), $token);
                        $session->set('2fa-verified', true);
                        return $this->redirectToRoute("frontoffice.dashboard.index");
                    } catch (\Exception $exception){
                        $error = $exception->getMessage();
                    }
                }
            } else {
                $OTPService->getOTPVerify($otp_method, $this->getUser(), $messageBus);
            }

            return $this->render("_security/verify.html.twig", [
                'error'         => $error,
                'otp_method'    => $otp_method,
                'user'          => $this->getUser()
            ]);
        }

    }