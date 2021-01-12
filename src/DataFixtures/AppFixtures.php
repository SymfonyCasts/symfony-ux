<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Color;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $colorRed = new Color('red', 'ff0000');
        $colorGreen = new Color('green', '00ff00');
        $colorBlue = new Color('blue', '0000ff');

        $manager->persist($colorRed);
        $manager->persist($colorGreen);
        $manager->persist($colorBlue);

        $category1 = new Category();
        $category1->setName('Office Supplies');
        $category2 = new Category();
        $category2->setName('Furniture');
        $category3 = new Category();
        $category3->setName('Breakroom');
        $category4 = new Category();
        $category4->setName('Snacks');

        $manager->persist($category1);
        $manager->persist($category2);
        $manager->persist($category3);
        $manager->persist($category4);

        $categories = [
            'office_supplies' => $category1,
            'furniture' => $category2,
            'break_room' => $category3,
        ];

        $brands = [
            'Faux-Trendster',
            'Low End Luxury',
            'Rest at Work',
        ];

        foreach (self::getProductsData() as $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setDescription($productData['description']);
            $product->setCategory($categories[$productData['category']]);
            $product->setImageFilename($productData['image']);
            $product->setPrice(rand(10, 50) * 100);
            $product->setStockQuantity(rand(10, 100));
            $product->setBrand($brands[array_rand($brands)]);

            if ($productData['with_colors'] ?? false) {
                $product->addColor($colorRed);
                $product->addColor($colorBlue);
                $product->addColor($colorGreen);
            }

            $manager->persist($product);
        }

        $fs = new Filesystem();
        $target = __DIR__.'/../../public/uploads';
        $fs->remove($target);
        $fs->mirror(__DIR__.'/uploads', $target);
        $fs->chmod($target, 0777);

        $user = new User();
        $user->setEmail('shopper@example.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'buy'));

        $manager->persist($user);

        $manager->flush();
    }

    private static function getProductsData()
    {
        /* OFFICE SUPPLIES */
        yield [
            'name' => 'Floppy disk',
            'description' => 'With 1.44 mb of storage space enjoy your favorite thumbnail photo on the go!',
            'image' => 'floppy-disc.png',
            'category' => 'office_supplies'
        ];
        yield [
            'name' => 'Blank CD\'s',
            'description' => 'Want to share your favorite tunes with a coworker? Then this set of blank cd\'s is for you! Load up your favorite motivational mixtape and share! (cd-rom drive not included)',
            'image' => 'blank-cds.png',
            'category' => 'office_supplies'
        ];
        yield [
            'name' => 'Disappearing Ink Pens',
            'description' => 'Write your daily to do list and watch it disappear on its own with our disappearing ink pen',
            'image' => 'pen.png',
            'category' => 'office_supplies'
        ];
        yield [
            'name' => 'Papers',
            'description' => 'Fresh stack of newspapers from the mid-90\'s for your enjoyment.',
            'image' => 'papers.png',
            'category' => 'office_supplies'
        ];

        /* FURNITURE */
        yield [
            'name' => 'Inflatable Sofa',
            'description' => 'Comfortable? No. Easy to move around the office and out to the trashcan? Totally.',
            'image' => 'inflatable-sofa.png',
            'category' => 'furniture',
            'with_colors' => true,
        ];
        yield [
            'name' => 'Lamp',
            'description' => 'Let this lamp light up your day!',
            'image' => 'dog-lamp.png',
            'category' => 'furniture',
        ];
        yield [
            'name' => 'Hammock',
            'description' => 'Feel sluggish part way through the work day? Get a refresh in our official office hammock. (beach views now included).',
            'image' => 'hammock.png',
            'category' => 'furniture',
            'with_colors' => true,
        ];
        yield [
            'name' => 'Art (Velvis)',
            'description' => 'Art with texture - there isn\'t a room (or a team) that Elvis can\'t pull together.',
            'image' => 'velvis.png',
            'category' => 'furniture',
        ];
        yield [
            'name' => 'Fake Plant',
            'description' => 'Bring a little life but not maintenance to your office with our premium faux office plants.',
            'image' => 'indoor-plant.png',
            'category' => 'furniture',
        ];

        /* BREAK ROOM */
        yield [
            'name' => 'Popcorn Machine',
            'description' => 'Your employees are *already* watching movies all day anyways. You might as well give them popcorn!',
            'image' => 'popcorn.png',
            'category' => 'break_room',
        ];
        yield [
            'name' => 'Pour-over Spigot',
            'description' => 'With our affordable pour-over spigot create the perfect caffeinated beverage to send your team into hyper-drive',
            'image' => 'spigot.png',
            'category' => 'break_room',
        ];
        yield [
            'name' => '3000 Piece Puzzle',
            'description' => ' Assorted pieces, may not contain one complete puzzle. Keep your team on their toes!',
            'image' => 'puzzle.png',
            'category' => 'break_room',
        ];
    }
}
