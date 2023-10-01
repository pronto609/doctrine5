<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $qestion = new Question();
        $qestion->setName('Missing pants')
            ->setSlug('missing-pants-'.rand(0,1000))
            ->setQuestion(<<<EOF
            Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. 
            Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. 
            Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat 
            massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. 
            In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis 
            eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. 
            Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, 
            enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut 
            metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. 
            Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, 
            tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque 
            sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec
             odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis 
             ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit 
             amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc?
             
        EOF
            );
        if (rand(1,10) > 2) {
            $qestion->setAskAt(
                new \DateTime(sprintf('-%d days', rand(1,100)))
            );
        }
        $manager->persist($qestion);
        $manager->flush();
    }
}
