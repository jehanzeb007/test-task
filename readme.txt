The Implementation:
A basket is set up as a class in PHP which allows adding items and computes the total with delivery charges and applicable offer rules baked in.

How It Works:
A basket is set up with a product catalog, delivery rules, and special offers enabled.
Products are added through the add method using its code.
The total method calculates the total cost after taking the special offer and the delivery rules into consideration.

Functions:
add($productCode): Adds the specified product to the basket.
applyOffer($productCode, $quantity): Applies logic for the offer to calculate the total cost for the product provided. 
total(): Calculates total costs after applying the offer and includes charges for delivery.

Example:
$basket = new Basket($catalog, $deliveryRules, $offers); 
$basket->add('R01'); $basket->add('G01'); 
echo 'Total: $' . $basket->total();

Examples:
products: B01, G01  total: $37.85
products: R01, R01  total: $54.37
products: R01, G01  total: $60.85
products: B01, B01, R01, R01, R01   total: $98.27
Assumptions The system uses the product codes, that are assumed to be valid, when adding items to the basket.
The special offer is only valid for Red Widgets and is calculated for each pair individually.
Offers are applied before delivery charges are computed.