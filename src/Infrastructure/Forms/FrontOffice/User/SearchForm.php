<?php
    namespace App\Infrastructure\Forms\FrontOffice\User;

    use App\Domain\_mysql\System\Froms\UserSearch;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class SearchForm extends AbstractType {

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder
                ->add('user', TextType::class, [
                    'label'     => 'search',
                    'required'  => false
                ])
                ->add('lastname', TextType::class, [
                    'label'     => 'lastname',
                    'required'  => false
                ])
                ->add('firstname', TextType::class, [
                    'label'     => 'firstname',
                    'required'  => false
                ])
                /**
                ->add('email', TextType::class, [
                    'label'     => 'email',
                    'required'  => false
                ])
                 * */
                ->add('sort', ChoiceType::class, [
                    'choices'   => [
                        'email'     => 'u.email'
                    ]
                ])
                ->add('order', ChoiceType::class, [
                    'choices'   => [
                        'ASC'   => 'ASC',
                        'DESC'  => 'DESC'
                    ]
                ])
                ->add('limit', ChoiceType::class, [
                    'choices'   => [
                        '5'     => 5,
                        '10'    => 10,
                        '20'    => 20,
                        '50'    => 50
                    ]
                ]);
        }

        public function configureOptions(OptionsResolver $resolver) {
            $resolver->setDefaults([
                'csrf_protection'   => false,
                'data_class'        => UserSearch::class,
                'method'            => 'get'
            ]);
        }

        public function getBlockPrefix() {
            return '';
        }

    }