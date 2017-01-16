<?php
namespace Pos;


class ProductNotFoundException extends \Exception {
    
}
class ProductPriceNotFoundException extends \Exception {
    
}

interface TemplateInterface
{
    public function scan($code);
    public function resetCart();
}


interface PriceListInterface {
    public function getUnitPrice($code);

}
class PriceList implements PriceListInterface {
    private $product_price = array('A'=>array(1=>2.00, 4=>7.00), 'B'=>array(1=>12.00), 'C'=>array(1=>1.25, 6=>6.00), 'D'=>array(1=>0.15));

    public function getUnitPrice($code){

    	if(isset($this->product_price[$code])){
    		if(isset($this->product_price[$code][1])){
    			return $this->product_price[$code][1];
    		}else
    		{
    			throw new ProductPriceNotFoundException("Product code $code not found");
    		}

    	}else{
    		throw new ProductNotFoundException("Product code $code not found");
    	}

    }
    public function getGroupPrice($code){
    	if(isset($this->product_price[$code])){
    		$group_unit = max(array_keys($this->product_price[$code]));

    		if(isset($this->product_price[$code][$group_unit])){
    			return $this->product_price[$code][$group_unit];
    		}else
    		{
    			throw new ProductPriceNotFoundException("Product code $code not found");
    		}
    	}else{
    		throw new ProductNotFoundException("Product code $code not found");
    	}
    }


    public function getGroupCount($code){
    	if(isset($this->product_price[$code])){
    		return $group_unit = max(array_keys($this->product_price[$code]));
    	}else{
    		throw new ProductNotFoundException("Product code $code not found");
    	}
    }
	public function setPricing($code, $price,$volume=1) {
		$this->product_price[$code][$volume]=$price;
    }


} 




class Terminal implements TemplateInterface  {
	private $cart;

	private $price_list;

	public $total;



	public function __construct() {
		$this->price_list=new PriceList();
	}
	public function setPricing($code, $price,$volume=1) {
		$this->price_list->setPricing($code, $price,$volume);
	}

	public function scan($code){
			if(isset($this->cart[$code])){
				$this->cart[$code]+=1;
			}else{
				$this->cart[$code]=1;
			}

		

		$this->total=$this->getTotal();

	}

	public function getTotal(){
		$total=0;
		try {
			foreach ($this->cart as $code => $volume) {
				$total+=((int)($volume / $this->price_list->getGroupCount($code) )) * $this->price_list->getGroupPrice($code) + $volume % $this->price_list->getGroupCount($code) * $this->price_list->getUnitPrice($code);
			}
		} catch (ProductNotFoundException $e) {
			echo $e->getMessage();exit;
		}
		catch(ProductPriceNotFoundException $e) {
			echo $e->getMessage();exit;
		}
		return $total;
	}

	public function resetCart(){
		$this->cart=[];
	}
	
}


