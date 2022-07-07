<?php

namespace PlaygroundFlow\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use PlaygroundFlow\Entity\OpenGraphAction;
use PlaygroundFlow\Entity\OpenGraphWebTechno;

/**
 *
 * @author greg
 * Use the command : ./vendor/bin/doctrine-module orm:fixtures:load
 * to install these data into database
 */
class LoadDictionaries extends AbstractFixture implements OrderedFixtureInterface
{

  protected $connection;
    /**
     * Load Actions
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
      $this->connection = $manager->getConnection();
      // print_r(get_class_methods($this->connection));
      // die('iii');

      //$manager->getConnection()->setNestTransactionsWithSavepoints(false);

      try {
        // Actions
        $action = new OpenGraphAction();
        $action->setCode('play');
        $action->setLabel('Play');
        $action->setDefinition('Play a game');
        if ($manager->getRepository('PlaygroundFlow\Entity\OpenGraphAction')->findOneBy(array('code' => $action->getCode())) == null) {
          $manager->persist($action);
          $manager->flush();
        }

        $action = new OpenGraphAction();
        $action->setCode('win');
        $action->setLabel('Win');
        $action->setDefinition('Win a game');
        if ($manager->getRepository('PlaygroundFlow\Entity\OpenGraphAction')->findOneBy(array('code' => $action->getCode())) == null) {
          $manager->persist($action);
          $manager->flush();
        }

        $action = new OpenGraphAction();
        $action->setCode('share');
        $action->setLabel('Share');
        $action->setDefinition('Share a game');
        if ($manager->getRepository('PlaygroundFlow\Entity\OpenGraphAction')->findOneBy(array('code' => $action->getCode())) == null) {
          $manager->persist($action);
          $manager->flush();
        }

        $action = new OpenGraphAction();
        $action->setCode('edit_profile');
        $action->setLabel('Edit profile');
        $action->setDefinition('Edit profile');
        if ($manager->getRepository('PlaygroundFlow\Entity\OpenGraphAction')->findOneBy(array('code' => $action->getCode())) == null) {
          $manager->persist($action);
          $manager->flush();
        }

        $action = new OpenGraphAction();
        $action->setCode('invite_game');
        $action->setLabel('Invite a player to play');
        $action->setDefinition('Invite a player to play');
        if ($manager->getRepository('PlaygroundFlow\Entity\OpenGraphAction')->findOneBy(array('code' => $action->getCode())) == null) {
          $manager->persist($action);
          $manager->flush();
        }

        // Web technos
        $techno = new OpenGraphWebTechno();
        $techno->setCode('playground');
        $techno->setLabel('Playground');
        $techno->setDefinition('This techno will list all stories that can be told on this platform');
        if ($manager->getRepository('PlaygroundFlow\Entity\OpenGraphWebTechno')->findOneBy(array('code' => $techno->getCode())) == null) {
          $manager->persist($techno);
          $manager->flush();
        }

        $techno = new OpenGraphWebTechno();
        $techno->setCode('shopify');
        $techno->setLabel('Shopify');
        $techno->setDefinition('This techno will list all stories that can be told on this platform');
        if (!$manager->getRepository('PlaygroundFlow\Entity\OpenGraphWebTechno')->findOneBy(array('code' => $techno->getCode())) == null) {
          $manager->persist($techno);
          $manager->flush();
        }

        $techno = new OpenGraphWebTechno();
        $techno->setCode('drupal');
        $techno->setLabel('Drupal');
        $techno->setDefinition('This techno will list all stories that can be told on this platform');
        if (!$manager->getRepository('PlaygroundFlow\Entity\OpenGraphWebTechno')->findOneBy(array('code' => $techno->getCode())) == null) {
          $manager->persist($techno);
          $manager->flush();
        }
        // store reference to admin role for User relation to Role
        // $this->addReference('admin-role', $adminRole);
        // $this->addReference('supervisor-role', $supervisorRole);
        // $this->addReference('game-role', $gameManagerRole);
      } catch (\Exception $e) {
        throw $e;
      }
    }

    public function getOrder()
    {
        return 4;
    }
}
