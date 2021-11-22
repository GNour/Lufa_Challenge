<?php

$productVolumes = [
    "40" => 12000,
    "33" => 2500,
    "35" => 1500,
    "41" => 1500,
    "34" => 500,
    "45" => 500,
];

// Associative array with a key as the productId and value that represents the quantity
$order = [
    "40" => 1,
    "33" => 4,
    "35" => 3,
    "41" => 1,
    "34" => 3,
    "45" => 1,
];

$boxes = packOrderInBoxesOptimizedApproch($order, $productVolumes);

printBoxes($boxes);

function printBoxes($boxes)
{
    print("\n\nOrder requires " . count($boxes) . " box(es)\n");

    foreach ($boxes as $key => $box) {
        print("\nIn box " . ++$key . ", we will pack\n");
        print("Product id \t Quantity\n");
        foreach ($box["products"] as $productId => $quantity) {
            print("\t" . $productId . " \t\t " . $quantity . "\n");
        }
    }
}

// Optimized Solution
function packOrderInBoxesOptimizedApproch($order, $productVolumes)
{
    $totalVolumeOfProducts = 0;
    foreach ($order as $productId => $quantity) {
        $totalVolumeOfProducts += $quantity * $productVolumes[$productId];
    }
    $totalVolumeOfProducts /= 15000;
    $numOfBoxes = ceil($totalVolumeOfProducts);
    $boxes = array_fill(0, $numOfBoxes, ["volumeAvailable" => 15000, "products" => []]);

    foreach ($order as $productId => $quantity) {
        foreach ($boxes as $key => $box) {
            // Keep base case to break after adding up the whole quantity ordered of a certain product
            if ($quantity == 0) {
                break;
            }
            $numOfQtyToAdd = floor($boxes[$key]["volumeAvailable"] / $productVolumes[$productId]);

            // Checks if numOfQtyToAdd in a certain box is greater than the ordered quantity
            if ($numOfQtyToAdd > 0 && $numOfQtyToAdd <= $quantity) {
                $boxes[$key]["products"][$productId] = $numOfQtyToAdd;
                $boxes[$key]["volumeAvailable"] -= $productVolumes[$productId] * $numOfQtyToAdd;
                $quantity -= $numOfQtyToAdd;
            } else if ($numOfQtyToAdd > $quantity) {
                $boxes[$key]["products"][$productId] = $quantity;
                $boxes[$key]["volumeAvailable"] -= $productVolumes[$productId] * $quantity;
                $quantity -= $quantity;
            }
        }
    }

    return $boxes;
}

// First Solution
function packOrderInBoxesFirstApproch($order, $productVolumes)
{
    $boxes = [
        [
            "volumeAvailable" => 15000,
            "products" => [],
        ],
    ];

    $numOfBoxes = 0;

    foreach ($order as $productId => $quantity) {
        $packed = 0;

        if ($boxes[$numOfBoxes]["volumeAvailable"] >= $productVolumes[$productId] * $quantity) {
            $boxes[$numOfBoxes]["products"][$productId] = $quantity;
            $boxes[$numOfBoxes]["volumeAvailable"] -= $productVolumes[$productId] * $quantity;
        } else {

            // Add the product to already used box with available volume
            foreach ($boxes as $key => $box) {
                $packed = 0;
                if ($boxes[$key]["volumeAvailable"] >= $productVolumes[$productId]) {
                    $boxes[$key]["products"][$productId] = ++$packed;
                    $boxes[$key]["volumeAvailable"] -= $productVolumes[$productId];

                    $quantity--;
                }
            }

            // Add the remaining quantity of a product to the boxes
            while ($quantity > 0) {
                if ($boxes[$numOfBoxes]["volumeAvailable"] >= $productVolumes[$productId]) {
                    $boxes[$numOfBoxes]["products"][$productId] = ++$packed;
                    $boxes[$numOfBoxes]["volumeAvailable"] -= $productVolumes[$productId];

                    $quantity--;
                } else {
                    $packed = 0;
                    $boxes[++$numOfBoxes] = [
                        "volumeAvailable" => 15000,
                        "products" => [],
                    ];
                }
            }

        }
    }
    return $boxes;
}
