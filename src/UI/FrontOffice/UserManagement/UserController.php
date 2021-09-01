<?php
    namespace App\UI\FrontOffice\UserManagement;

    use App\Application\Annotation\Breadcrumb\Breadcrumb;
    use App\Application\Services\AWSS3Service;
    use App\Domain\_mysql\System\Entity\User;
    use App\Domain\_mysql\System\Froms\UserSearch;
    use App\Domain\_mysql\System\Repository\UserRepository;
    use App\Infrastructure\Forms\FrontOffice\User\NewForm as NewUserForm;
    use App\Infrastructure\Forms\FrontOffice\User\SearchForm as SearchUserForm;
    use Knp\Component\Pager\PaginatorInterface;
    use Otp\GoogleAuthenticator;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Breadcrumb("root", label="home", route="root")
     * @Breadcrumb("usermanagement", label="user management", route="root", parent="root")
     * @Breadcrumb("index", label="users", route="frontoffice.users.index", parent="usermanagement")
     * @Breadcrumb("new", label="new", route="frontoffice.users.new", parent="index")
     * @Breadcrumb("show", label="#user.fullname#", route="frontoffice.users.show", params={"user"}, parent="index")
     * @Breadcrumb("show.overview", label="overview", route="frontoffice.users.show.overview", params={"user"}, parent="show")
     * @Breadcrumb("edit", label="edit #user.fullname#", route="frontoffice.users.edit", params={"user"}, parent="index")
     * @Breadcrumb("edit.overview", label="overview", route="frontoffice.users.edit.overview", params={"user"}, parent="edit")
     * @Breadcrumb("delete", label="delete", route="frontoffice.users.delete", params={"user"}, parent="edit")
     *
     * @Route("users", name="users.")
     */
    class UserController extends AbstractController {

        /**
         * @Breadcrumb("index")
         * @Route(".html", name="index", methods={"GET"})
         */
        public function index(Request $request, UserRepository $userRepository, PaginatorInterface $paginator){
            $search = new UserSearch();

            $form['search'] = $this->createForm(SearchUserForm::class, $search)->handleRequest($request);

            $users = $paginator->paginate($userRepository->findSearch($search), $request->query->getInt('page', 1), $search->getLimit());

            return $this->render("FrontOffice/User/index.html.twig", [
                'forms' => [
                    'search'    => $form['search']->createView()
                ],
                'search'    => $search,
                'users'     => $users
            ]);
        }

        /**
         * @Breadcrumb("new")
         * @Route("/new.html", name="new", methods={"GET", "POST"})
         */
        public function new(Request $request, AWSS3Service $AWSS3){
            $user = new User();

            $form['new'] = $this->createForm(NewUserForm::class, $user)->handleRequest($request);

            if($form['new']->isSubmitted() && $form['new']->isValid()){
                $avatar = $form['new']->get('avatar')->getData();
                if($avatar) $user->setAvatar($AWSS3->putObjectUpload('user', $avatar));

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('frontoffice.users.index');
            }

            return $this->render("FrontOffice/User/new.html.twig", [
                'form'  => [
                    'new'   => $form['new']->createView()
                ]
            ]);
        }

        /**
         * @Breadcrumb("show")
         * @Route("/{user}.html", name="show", methods={"GET"})
         */
        public function show(User $user){
            return $this->redirectToRoute("frontoffice.users.show.overview", ['user' => $user]);
        }

        /**
         * @Breadcrumb("show.overview")
         * @Route("/{user}/overview.html", name="show.overview", methods={"GET"})
         */
        public function showOverview(User $user){
            return $this->render("FrontOffice/User/show.overview.html.twig", [
                'user'  => $user
            ]);
        }

        /**
         * @Breadcrumb("edit")
         * @Route("/{user}/edit.html", name="edit", methods={"GET"})
         */
        public function edit(User $user){
            return $this->redirectToRoute("frontoffice.users.edit.overview", ['user' => $user]);
        }

        /**
         * @Breadcrumb("edit.overview")
         * @Route("/{user}/edit/overview.html", name="edit.overview", methods={"GET"})
         */
        public function editOverview(Request $request, User $user){
            return $this->render("FrontOffice/User/edit.overview.html.twig", [
                'user'  => $user
            ]);
        }

        /**
         * @Breadcrumb("edit.security")
         * @Route("/{user}/edit/security.html", name="edit.security", methods={"GET"})
         */
        public function editSecurity(Request $request, User $user){
            if($this->isCsrfTokenValid('up-'.$user->getId(), $request->get('_token'))){
                $id0 = $request->get('id');
                $id1 = $id0 - 1;
                $data = $user->getOtp();
                $data0 = $data[$id0];
                $data1 = $data[$id1];
                $data[$id0] = $data1;
                $data[$id1] = $data0;
                $user->setOtp($data);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('frontoffice.users.edit.security', ['user' => $user]);
            } elseif($this->isCsrfTokenValid('down-'.$user->getId(), $request->get('_token'))){
                $id0 = $request->get('id');
                $id1 = $id0 + 1;
                $data = $user->getOtp();
                $data0 = $data[$id0];
                $data1 = $data[$id1];
                $data[$id0] = $data1;
                $data[$id1] = $data0;
                $user->setOtp($data);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('frontoffice.users.edit.security', ['user' => $user]);
            }

            if($user->getOtpCodeSecret() === null){
                $secret = GoogleAuthenticator::generateRandom();
                $qrcode = GoogleAuthenticator::getQrCodeUrl('totp', 'Symfony docker project '.$user->getEmail(), $secret);
            }

            return $this->render("FrontOffice/User/edit.security.html.twig", [
                'otp'   => [
                    'qrcode'    => $qrcode,
                    'secret'    => $secret
                ],
                'user'      => $user
            ]);
        }

        /**
         * @Breadcrumb("delete")
         * @Route("/{user}/delete.html", name="delete", methods={"GET"})
         */
        public function delete(Request $request, User $user, AWSS3Service $AWSS3){
            if($this->isCsrfTokenValid('delete-'.$user->getId(), $request->get('_token'))){
                if($user->getAvatar()) $AWSS3->deleteObjectUpload('user', $user->getAvatar());

                $em = $this->getDoctrine()->getManager();
                $em->remove($user);
                $em->flush();

                return $this->redirectToRoute('frontoffice.users.index');
            }
            return $this->render("FrontOffice/User/delete.html.twig", [
                'user'  => $user
            ]);
        }

    }