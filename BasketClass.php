<?php
class Basket {
    // This holds the product catalog with product codes and their prices
    private $catalog;

    // Delivery rules to calculate shipping costs based on total price
    private $deliveryRules;

    // Special offers available on certain products
    private $offers;

    // Keeps track of products added to the basket and their quantities
    private $items = [];

    // Set up the basket with the product catalog, delivery rules, and offers
    public function __construct($catalog, $deliveryRules, $offers) {
        $this->catalog = $catalog;
        $this->deliveryRules = $deliveryRules;
        $this->offers = $offers;
    }

    // Add a product to the basket; increase quantity if it already exists
    public function add($productCode) {
        if (isset($this->items[$productCode])) {
            $this->items[$productCode]++;
        } else {
            $this->items[$productCode] = 1;
        }
    }

    // Apply offers (if any) and calculate the total price for a product
    private function applyOffer($productCode, $quantity) {
        $total = 0;

        // Check if the product 'R01' has a 'buy one, get one half price' offer
        if ($productCode == 'R01' && $quantity > 1) {
            // Calculate how many items will be at half price
            $halfPriceCount = floor($quantity / 2);

            // The rest will be at full price
            $fullPriceCount = $quantity - $halfPriceCount;

            // Add the cost for full-price items
            $total += $fullPriceCount * $this->catalog[$productCode];

            // Add the cost for half-price items
            $total += $halfPriceCount * ($this->catalog[$productCode] / 2);
        } else {
            // If no offer applies, just calculate the regular total
            $total += $this->catalog[$productCode] * $quantity;
        }

        return $total;
    }

    // Calculate the total price of the basket, including delivery costs
    public function total() {
        $total = 0;

        // Go through each item in the basket and calculate its total
        foreach ($this->items as $code => $quantity) {
            $total += $this->applyOffer($code, $quantity);
        }

        // Set delivery cost to zero by default
        $delivery = 0;

        // Find the correct delivery charge based on the basket total
        foreach ($this->deliveryRules as $rule) {
            if ($total >= $rule['limit']) {
                $delivery = $rule['cost'];
                break; // Stop checking once we find a matching rule
            }
        }

        // Add the delivery cost to the total price
        $total += $delivery;

        // Return the final amount formatted to 2 decimal places
        return number_format($total, 2);
    }
}

// List of products and their prices
$catalog = [
    'R01' => 32.95,
    'G01' => 24.95,
    'B01' => 7.95,
];

// Delivery charges based on the total basket value
$deliveryRules = [
    ['limit' => 90, 'cost' => 0],       // Free delivery if total is $90 or more
    ['limit' => 50, 'cost' => 2.95],    // $2.95 delivery if total is $50 or more
    ['limit' => 0, 'cost' => 4.95],     // $4.95 delivery for everything else
];

// Special offers on certain products
$offers = [
    'R01' => 'buy one get one half price',
];

// Unit Testing Rules:
// 1. Test the basket with various product combinations to ensure correct totals.
// 2. Validate that special offers (e.g., buy one get one half price) are applied correctly.
// 3. Ensure delivery costs are calculated based on the total amount.
// 4. Check edge cases like empty baskets, single items, and multiple offers.
// 5. Confirm the output is formatted to two decimal places.
// 6. Test with large quantities and unusual combinations.

// Test cases to check the basket's total calculation
$basket = new Basket($catalog, $deliveryRules, $offers);
$basket->add('B01'); // Add a Blue Widget
$basket->add('G01'); // Add a Green Widget
echo 'Total: $' . $basket->total() . "<br>"; // Expected: $37.85

$basket = new Basket($catalog, $deliveryRules, $offers);
$basket->add('R01'); // Add a Red Widget
$basket->add('R01'); // Add another Red Widget
echo 'Total: $' . $basket->total() . "<br>"; // Expected: $54.37

$basket = new Basket($catalog, $deliveryRules, $offers);
$basket->add('R01'); // Add a Red Widget
$basket->add('G01'); // Add a Green Widget
echo 'Total: $' . $basket->total() . "<br>"; // Expected: $60.85

$basket = new Basket($catalog, $deliveryRules, $offers);
$basket->add('B01'); // Add 2 Blue Widgets
$basket->add('B01');
$basket->add('R01'); // Add 3 Red Widgets
$basket->add('R01');
$basket->add('R01');
echo 'Total: $' . $basket->total() . "<br>"; // Expected: $98.27

$basket = new Basket($catalog, $deliveryRules, $offers);
$basket->add('B01'); // Add 2 Blue Widgets
$basket->add('B01');
$basket->add('R01'); // Add 4 Red Widgets
$basket->add('R01');
$basket->add('R01');
$basket->add('R01');
echo 'Total: $' . $basket->total() . "<br>"; // Expected: $131.22
?>
