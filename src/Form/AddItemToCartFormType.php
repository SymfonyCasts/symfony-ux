<?php

namespace App\Form;

use App\Entity\CartItem;
use App\Entity\Color;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class AddItemToCartFormType extends AbstractType
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $product = $options['product'];
        if (!$product instanceof Product) {
            throw new \InvalidArgumentException('The product option should be a Product object');
        }

        // sets a default data to use
        if (!($options['data'] ?? null)) {
            $builder->setData(new CartItem($product));
        }

        // set the action attribute for this form
        $builder->setAction($this->router->generate(
            'app_cart_add_item',
            ['id' => $product->getId()]
        ));

        $builder
            ->add('quantity', IntegerType::class)
        ;

        if ($product->hasColors()) {
            $builder->add('color', EntityType::class, [
                'class' => Color::class,
                'choices' => $product->getColors(),
                'placeholder' => 'Color',
                'choice_label' => 'name',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CartItem::class,
        ]);
        $resolver->setRequired(['product']);
    }
}
