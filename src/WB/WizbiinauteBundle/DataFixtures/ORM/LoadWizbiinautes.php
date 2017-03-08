<?php

namespace WB\WizbiinauteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WB\WizbiinauteBundle\Entity\Wizbiinaute;

class LoadWizbiinautes implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$WBNautes = array
		(
			0 => array('DUPONT', 'Jean', 123),
			1 => array('LEFRANC', 'Martin', 456),
			2 => array('MARTIN', 'Alice', 789),
			3 => array('PECHU', 'Pierre', 741),
			4 => array('FITOU', 'Carine', 852),
			5 => array('ROCHE', 'Marc', 963),
			6 => array('VALET', 'Stéphane', 159),
			7 => array('GROLIER', 'Chloé', 357),
			8 => array('WASSON', 'Emeric', 'emeric-wasson'),
			9 => array('ALVADO', 'Rémi', 'remi-alvado')
		);

	// 
		foreach ($WBNautes as $element)
		{
			
		// On crée l'utilisateur
			$Wizbiinaute = new Wizbiinaute;

		// Le nom d'utilisateur et le mot de passe sont identiques pour l'instant
			$Wizbiinaute->setLastName($element[0]);
			$Wizbiinaute->setFirstName($element[1]);
			$Wizbiinaute->setWui($element[2]);

		// On le persiste
			$manager->persist($Wizbiinaute);
		}

	// On déclenche l'enregistrement
		$manager->flush();
	}
}