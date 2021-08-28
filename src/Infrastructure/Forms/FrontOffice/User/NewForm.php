<?php
    namespace App\Infrastructure\Forms\FrontOffice\User;

    use App\Domain\_mysql\System\Entity\User;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Validator\Constraints\File;

    class NewForm extends AbstractType {

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder
                ->add('avatar', FileType::class, [
                    'label'         => 'avatar',
                    'mapped'        => false,
                    'required'      => false,
                    'help'          => 'JPG or PNG, <4Mb',
                    'constraints'   => [
                        new File([
                            'maxSize'   => '4M',
                            'mimeTypes'  => [
                                'image/jpeg',
                                'image/png'
                            ],
                            'mimeTypesMessage'  => 'Please upload a valid image'
                        ])
                    ]
                ])
                ->add('lastname', TextType::class, [
                    'label'     => 'lastname',
                    'required'  => true
                ])
                ->add('firstname', TextType::class, [
                    'label'     => 'firstname',
                    'required'  => true
                ])
                ->add('jobTitle', TextType::class, [
                    'label'     => 'jobTitle',
                    'required'  => true
                ])
                ->add('email', TextType::class, [
                    'help'      => 'please use a valid email',
                    'label'     => 'email',
                    'required'  => true
                ])
                ->add('locale', ChoiceType::class, [
                    'choices'   => [
                        'English'   => 'en',
                        'Français'  => 'fr'
                    ],
                    'label'     => 'locale',
                    'required'  => true
                ])
                ->add('password', RepeatedType::class, [
                    'type'              => PasswordType::class,
                    'invalid_message'   => 'the password fields must match.',
                    'options'           => ['attr' => ['class' => 'password-field']],
                    'required'          => true,
                    'first_options'     => ['label' => 'password'],
                    'second_options'    => ['label' => 'repeat password']
                ])
                ->add('dateStarted', DateTimeType::class, [
                    'label'         => 'account start date',
                    'widget'        => 'single_text'
                ])
                ->add('locked', CheckboxType::class, [
                    'label'     => 'locked',
                    'required'  => false
                ])
                ->add('enabled', CheckboxType::class, [
                    'label'     => 'enabled',
                    'required'  => false
                ]);
        }

        public function configureOptions(OptionsResolver $resolver) {
            $resolver->setDefaults([
                'data_class'    => User::class
            ]);
        }

    }