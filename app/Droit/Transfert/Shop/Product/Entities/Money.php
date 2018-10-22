<?php namespace App\Droit\Shop\Product\Entities;

class Money {

    /**
     * Rate of VAT
     * @var float
     */
    private $vat;

    /**
     * Currency symbol
     * @var string
     */
    private $symbol;

    /**
     * Total
     * @var float
     */
    private $total;

    /**
     * Total VAT
     * @var float
     */
    private $totalVat;

    /**
     * Total of products with VAT
     * @var float
     */
    private $totalProductsWithVat;

    /**
     * Total VAT from products with VAT
     * @var float
     */
    private $totalProductsWithVatVat;

    /**
     * Total products without VAT
     * @var float
     */
    private $totalProductsWithoutVat;

    /**
     * Constructor
     *
     * Sets the rate of VAT
     * Sets the currency symbol
     */
    public function __construct() {
        // Set VAT
        $this->vat = 0.25;
        // Set the currency symbol
        $this->symbol = "Fr.";

        // Set totals
        $this->total    = 0.00;
        $this->totalVat = 0.00;
        $this->totalProductsWithVat     = 0.00;
        $this->totalProductsWithVatVat  = 0.00;
        $this->totalProductsWithoutVat  = 0.00;
    }

    /**
     * Format to currency
     *
     * Rounds a number to 2 decimal places
     * Formats the number to 2 decimal places
     *
     * @param float Number to be formatted
     * @return float Formatted number
     */
    public function format($input) {
        // Round number and format to 2 decimal places
        //return number_format(round($input, 2), 2);
        return number_format((float)$input, 2, '.', '');
    }

    /**
     * VAT
     *
     * Calculates and returns the formatted VAT
     *
     * @param float Price to calc VAT from
     * @return float VAT amount
     */
    public function vat($input) {
        // Return formatted Vat from input amount
        return $this->format($input * $this->vat);
    }

    /**
     * Add
     *
     * Add to running total
     * If VAT is set, get VAT and add to total
     * Else, just add to total
     * Return the amount of VAT
     *
     * @param float The price of the item
     * @param bool If VAT is set
     * @return float The amount of VAT
     */
    public function add($input, $vat = NULL) {
        // If VAT is set
        if($vat == true){
            // Get the VAT
            $vat = $this->vat($input);
            // Add to running total
            $this->total += ($input + $vat);
            // Add to running VAT total
            $this->totalVat += $vat;
            // Add to products with VAT total
            $this->totalProductsWithVat += $input;
            // Add VAT to products with VAT total
            $this->totalProductsWithVatVat += $vat;
            // Return the amount of Vat
            return $vat;
        }else{
            // Add to running total
            $this->total += $input;
            // Add to products without VAT total
            $this->totalProductsWithoutVat += $input;
            // Return 0.00 Vat
            return $this->format(0.00);
        }
    }

    /**
     * Display
     *
     * Display the amount
     * Add VAT if applicable
     * Add currency symbol
     * Return formatted
     *
     * @param float The price of the item
     * @param bool If VAT is set
     * @return float The formatted price
     */
    public function display($input, $vat = NULL) {
        // If VAT is set
        if($vat == true){
            // Get VAT
            $vat = $this->vat($input);
            // Add VAT to input
            $input += $vat;
            // Return the formatted total
            return $this->symbol . $this->format($input);
        }else{
            // Return formatted
            return $this->symbol . $this->format($input);
        }
    }

    /**
     * Display VAT
     *
     * Display the rate of VAT
     * as a percentage
     *
     * @return string e.g "20%";
     */
    public function displayVat(){
        return ($this->vat * 100). "%";
    }

    /*
     * Total
     *
     * Return the running total
     * as either a float or a formatted string
     *
     * @param string Type of formatting
     * @return float
     */
    public function total($type = NULL) {
        // Return running total
        if($type == "string"){
            // Return formated as a string
            return $this->symbol . $this->format($this->total);
        }else{
            // Return as float
            return $this->format($this->total);
        }
    }

    /*
     * Total VAT
     *
     * Return the running VAT total
     * as either a float or a formatted string
     *
     * @param string Type of formatting
     * @return float
     */
    public function totalVat($type = NULL) {
        // Return running VAT total
        if($type == "string"){
            // Return formatted as a string
            return $this->symbol . $this->format($this->totalVat);
        }else{
            // Return as float
            return $this->format($this->totalVat);
        }
    }

    /*
     * Total minus VAT
     *
     * Return the total minus VAT
     *
     * @param string Type of formatting
     * @return float
     */
    public function totalMinusVat($type = NULL){
        // Return Total minus VAT
        if($type == "string"){
            // Return formatted as a string
            return $this->symbol . $this->format($this->total - $this->totalVat);
        }else{
            // Return as float
            return $this->format($this->total - $this->totalVat);
        }
    }

    /*
     * Total of products with VAT
     *
     * Returns a running total of
     * the value of products with VAT
     *
     * @param string Type of formatting
     * @return string As string
     * @return float As float
     */
    public function totalProductsWithVat($type = NULL){
        if($type == "string"){
            return $this->symbol . $this->format($this->totalProductsWithVat);
        }else{
            $this->format($this->totalProductsWithVat);
        }
    }

    /*
     * Total of products without VAT
     *
     * Returns a running total of
     * the value of products without VAT
     *
     * @param string Type of formatting
     * @return string As string
     * @return float As float
     */
    public function totalProductsWithoutVat($type = NULL){
        if($type == "string"){
            return $this->symbol . $this->format($this->totalProductsWithoutVat);
        }else{
            return $this->format($this->totalProductsWithoutVat);
        }
    }

}