<?php

namespace App\DataFixtures;

use App\Entity\Discussion;
use App\Entity\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DiscussionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($j=0;$j<7;$j++){
            $theme = new Theme();
            $theme->setTitle("Theme n°$j");
            $theme->setCreatedAt(new \DateTime());
            $theme->setContent("<div>Dans ce theme on parle de blabla</div>");
            $manager->persist($theme);

        for ($i=0;$i<mt_rand(3,6);$i++){

            $discussion = new Discussion();
            $discussion->setCreatedAt(new \DateTime());
            $discussion->setTitle("titre de la discussion n°$i");
            $discussion->setContent("<div>Ceci est le contenu de la discussion numero $i</div>");
            $discussion->setTheme($theme);
            $manager->persist($discussion);

        }}
        $manager->flush();
    }
}
