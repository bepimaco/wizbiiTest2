<?php

namespace \WB\WizbiinauteBundle\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WizbiinautesRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testSearchByWui()
    {
        $wbNaute = $this->em
            ->getRepository('WBWizbiinauteBundle:Wizbiinaute')
            ->findOneBy(array('wui' => 123))
        ;

        $this->assertCount(1, $wbNaute);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}