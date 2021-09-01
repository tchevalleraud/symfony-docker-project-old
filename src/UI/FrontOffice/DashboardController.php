<?php
    namespace App\UI\FrontOffice;

    use App\Application\Annotation\Breadcrumb\Breadcrumb;
    use App\Infrastructure\Message\Test\TestEmailMessage;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Messenger\MessageBusInterface;
    use Symfony\Component\Messenger\Stamp\DelayStamp;
    use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Breadcrumb("root", label="home", route="root")
     * @Breadcrumb("index", label="dashboard", route="frontoffice.dashboard.index", parent="root")
     * @Breadcrumb("index2", label="dashboard v2", route="frontoffice.dashboard.v2", parent="root")
     * @Breadcrumb("index3", label="dashboard v3", route="frontoffice.dashboard.v3", parent="root")
     *
     * @Route("dashboard", name="dashboard.")
     */
    class DashboardController extends AbstractController {

        /**
         * @Breadcrumb("index")
         * @Route(".html", name="index", methods={"GET"})
         */
        public function index(){
            return $this->render("FrontOffice/Dashboard/index.html.twig");
        }

        /**
         * @Breadcrumb("index2")
         * @Route("-2.html", name="v2", methods={"GET"})
         */
        public function index2(){
            return $this->render("FrontOffice/Dashboard/index2.html.twig");
        }

        /**
         * @Breadcrumb("index3")
         * @Route("-3.html", name="v3", methods={"GET"})
         */
        public function index3(){
            return $this->render("FrontOffice/Dashboard/index3.html.twig");
        }

        /**
         * @Route("-test-message.html", name="test.message", methods={"GET"})
         */
        public function testMessage(){
            $this->dispatchMessage(new TestEmailMessage("tchevalleraud@gmail.com", "test", "ceci est un test"), [new DelayStamp(45000)]);
            return $this->redirectToRoute("frontoffice.dashboard.v3");
        }

    }