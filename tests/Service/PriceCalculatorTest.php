<?php

namespace App\Tests\Service; // Namespace de l’espace de test correspondant au service testé

use App\Service\PriceCalculator; // On importe la classe métier à tester
use PHPUnit\Framework\TestCase; // On hérite de TestCase, base de tout test unitaire PHPUnit

class PriceCalculatorTest extends TestCase // Début de la classe de test
{
    // 🧪 Test n°1 : Cas classique avec une remise valide
    public function testApplyDiscountWithValidValues(): void
    {
        $calculator = new PriceCalculator(); // On instancie notre service
        $result = $calculator->applyDiscount(100.0, 20.0); // On applique une remise de 20% sur 100€
        $this->assertEquals(80.0, $result); // On s’attend à ce que le résultat soit 80€
    }

    // 🧪 Test n°2 : Cas où la remise est de 0%
    public function testApplyDiscountWithZeroDiscount(): void
    {
        $calculator = new PriceCalculator();
        $result = $calculator->applyDiscount(50.0, 0.0); // Aucune remise sur 50€
        $this->assertEquals(50.0, $result); // Résultat attendu : 50€
    }

    // 🧪 Test n°3 : Cas d’erreur avec une remise au-dessus de 100%
    public function testApplyDiscountWithInvalidDiscountAbove100(): void
    {
        $calculator = new PriceCalculator();

        $this->expectException(\InvalidArgumentException::class); // On s’attend à une exception
        $calculator->applyDiscount(100.0, 150.0); // 150% est une remise invalide → exception levée
    }

    // 🧪 Test n°4 : Cas d’erreur avec une remise négative
    public function testApplyDiscountWithNegativeDiscount(): void
    {
        $calculator = new PriceCalculator();

        $this->expectException(\InvalidArgumentException::class); // Là aussi, on teste l’erreur
        $calculator->applyDiscount(100.0, -10.0); // Remise négative invalide → exception levée
    }
}
